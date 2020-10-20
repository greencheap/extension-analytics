<template>
    <div>
        <div class="uk-card-badge">
            <ul class="uk-iconnav uk-invisible-hover">
                <li v-show="!editing">
                    <a uk-icon="file-edit" class="uk-link-muted" :title="'Edit' | trans" uk-tooltip="delay: 500" @click.prevent="$parent.edit" />
                </li>
                <li v-show="!editing">
                    <a uk-icon="more-vertical" class="uk-link-muted uk-sortable-handle" :title="'Drag' | trans" uk-tooltip="delay: 500" />
                </li>
                <li v-show="editing">
                    <a v-confirm="'Delete widget?'" uk-icon="trash" :title="'Delete' | trans" uk-tooltip="delay: 500" @click.prevent="$parent.remove" />
                </li>
                <li v-show="editing">
                    <a uk-icon="check" :title="'Close' | trans" uk-tooltip="delay: 500" @click.prevent="parentSave" />
                </li>
            </ul>
        </div>

        <div v-if="editing" class="uk-card-header pk-panel-teaser">

            <div class="uk-margin-top">
                <label class="uk-form-label">{{ 'Report Style' | trans }}</label>
                <div class="uk-form-controls">
                    <select class="uk-select uk-width-expand" v-model="widget.explorer.explorer">
                        <option v-for="(exp , id) in explorer" :key="id" :value="exp.name">{{ exp.alias }}</option>
                    </select>
                </div>
            </div>

            <div class="uk-child-width-1-2 uk-margin" uk-grid>
                <div>
                    <div class="uk-margin">
                        <label class="uk-form-label">{{ 'Tag' | trans }}</label>
                        <div class="uk-form-controls">
                            <select class="uk-select uk-width-expand" v-model="widget.explorer.tag">
                                <option v-for="(tg , id) in getTagFor" :key="id" :value="tg">{{ capitalizeText(tg) }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="uk-margin">
                        <label class="uk-form-label">{{ 'Value' | trans }}</label>
                        <div class="uk-form-controls">
                            <select class="uk-select uk-width-expand" v-model="widget.explorer.name">
                                <option v-for="(nm , id) in getNames" :key="id" :value="nm.name">{{ nm.alias }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label">{{ 'Date' | trans }}</label>
                <div class="uk-form-controls">
                    <select class="uk-select uk-width-expand" v-model="widget.analytics.startDate">
                        <option value="1daysAgo">{{ '1 Days Ago' | trans}}</option>
                        <option value="7daysAgo">{{ '7 Days Ago' | trans}}</option>
                        <option value="30daysAgo">{{ '30 Days Ago' | trans}}</option>
                        <option value="120daysAgo">{{ '120 Days Ago' | trans}}</option>
                    </select>
                </div>
            </div>

            <div v-if="widget.explorer.tag === 'dimensions'" class="uk-margin">
                <label class="uk-form-label">{{ 'Chart' | trans }}</label>
                <div class="uk-form-controls">
                    <select class="uk-select uk-width-expand" v-model="widget.chart">
                        <option v-for="(chart , id) in charts" :key="id" :value="chart.name">{{ chart.label }}</option>
                    </select>
                </div>
            </div>

            <div v-if="config.view_id">
                <button class="uk-button uk-button-small uk-button-primary" @click.prevent="openEditing">{{ 'Open The Settings' | trans }}</button>
            </div>
        </div>

        <div class="uk-card-body uk-card-small">
            <div v-if="config.view_id && loader" class="uk-text-center">
                <v-loader />
            </div>

            <div v-if="config.view_id && !loader">
                <div v-if="resData.explorer === 'metrics'">
                    <div class="uk-height-small uk-flex uk-flex-middle uk-flex-center">
                        <div class="uk-text-center">
                            <span class="uk-display-block">{{getHeaderTitle}}</span>
                            <h1 class="uk-heading-medium uk-margin-remove">{{resData.data}}</h1>
                        </div>
                    </div>
                </div>

                <div v-else>
                    <h1 class="uk-h4 uk-margin-remove">{{getHeaderTitle}}</h1>
                    <component :is="widget.chart" :report="resData" class="uk-margin-top"></component>
                </div>
            </div>

            <div class="uk-margin-medium-top uk-text-center" v-if="!config.view_id">
                <p>{{'The widget information is not configured correctly. Please update the settings.' | trans}}</p>
                <button class="uk-button uk-button-small uk-button-primary" @click.prevent="openEditing">{{ 'Open The Settings' | trans }}</button>
            </div>
        </div>

        <v-modal ref="editAnalyticsSettings">
            <div class="uk-modal-body">
                <h2 class="uk-modal-title">{{ 'Google Analytics Settings' | trans }}</h2>
                <div>
                    <div class="uk-margin">
                        <label class="uk-form-label">{{ 'View Id' | trans }}</label>
                        <div class="uk-form-controls uk-flex">
                            <input type="text" class="uk-input uk-width-expand" :placeholder="'Google Analytics View Id' | trans" v-model="modalConfigure.view_id">
                            <button id="runTestButton" class="uk-button uk-button-default tm-form-margin" type="button" @click.prevent="runTest()" :disabled="!modalConfigure.view_id.length">{{ 'Test API' | trans }}</button>
                        </div>
                    </div>
                    <div v-if="error.msg.length" :class="{'uk-text-danger':error.status != 200,'uk-text-primary':error.status != 200}">
                        <p>{{error.msg}}</p>
                    </div>
                    <div class="uk-margin">
                        <p>{{ 'In order to extract data from your property, you must give viewable authorization to the following Email address.' | trans}}</p>
                        <pre><code>greencheap@greencheap.iam.gserviceaccount.com</code></pre>
                        <a href="https://greencheap.net/docs/google-analytics" target="_blank">{{ 'Go To Analytics Docs' | trans }}</a>
                    </div>
                    <div class="uk-margin uk-text-right">
                        <a @click.prevent="save" class="uk-button uk-button-primary">{{ 'Save' | trans }}</a>
                    </div>
                </div>
            </div>
        </v-modal>
    </div>
</template>

<script>
const Analytics = {
    name: 'analytics',

    type: {
        id: 'analytics',
        label: 'Google Analytics',
        disableToolbar: true,
        description() {

        },
        defaults: {
            analytics:{
                startDate: '1daysAgo',
                endDate: 'today',
            },
            explorer:{
                explorer: 'user',
                tag: 'dimensions',
                name: 'ga:userType',
                alias: 'User Type'
            },
            chart:'IntervalChart'
        },
    },

    replace: false,

    props: ['widget', 'editing'],

    mixins:[Library],

    data() {
        return {
            loader:false,
            config:window.$analytics,
            explorer: window.$explorer,
            modalConfigure:{
                view_id: ''
            },
            error:{
                status: '',
                msg:''
            },
            resData:false,
            charts:[]
        };
    },

    watch:{
        'widget.explorer.explorer':{
            handler(val){
                this.changeExplorer(val)
            },
            deep:true
        },
        'widget.explorer.tag':{
            handler(val){
                const expName = this.widget.explorer.explorer;
                _.find(this.explorer , (exp)=>{
                    if(exp.name === expName){
                        let getInfo = exp.tag[this.widget.explorer.tag][0];
                        this.widget.explorer.name = getInfo.name
                    }
                });
            },
            deep:true
        }
    },

    created(){

        const charts = [];
        _.forIn(this.$options.components, (component, name) => {
            if (component.chart) {
                charts.push(_.extend({ name, priority: 0 }, component.chart));
            }
        });
        this.$set(this, 'charts', charts);

        if(this.config.view_id){
            this.loader = true
            this.load()
        }
    },

    methods:{
        capitalizeText(text) {
            return text.toUpperCase();
        },

        load(){
            this.$http.get('admin/api/analytics/getreport' , {
                params: {
                    view_id: this.config.view_id,
                    analytics: this.widget.analytics,
                    explorer: this.widget.explorer
                }
            }).then((res) => {
                this.$set(this , 'resData' , res.data.data)
                this.loader = false
            }).catch((err) => {
                this.$notify(this.$trans(err.bodyText) , 'danger')
            })
        },

        changeExplorer(expName){
            _.find(this.explorer, (exp) => {
                if(exp.name === expName){
                    this.widget.explorer.tag = exp.default_tag;
                    const defaultElement = exp.tag[exp.default_tag][0];
                    this.widget.explorer.name = defaultElement.name;
                    this.widget.explorer.alias = exp.name;
                }
            })
        },

        openEditing(){
            this.modalConfigure.view_id = this.config.view_id;
            this.$refs.editAnalyticsSettings.open()
        },

        save(){
            this.config.view_id = this.modalConfigure.view_id;
            this.$refs.editAnalyticsSettings.close()
            this.$notify(this.$trans('Wait...'));
            this.$http.post('admin/system/settings/config', { name: 'analytics', config: this.config }).then(function () {
                this.$notify('Settings saved.', '');
                this.load();
            }).catch(function (response) {
                this.$notify(response.message, 'danger');
            })
        },

        runTest(){
            this.buttonLoader('runTestButton' , true , this.$trans('Test API'))
            this.$http.get('admin/api/analytics/testapi', {
                params: {
                    view_id:this.modalConfigure.view_id
                }
            }).then((res) => {
                this.error = {
                    status:res.status,
                    msg:res.data.msg
                }
            }).catch((err) => {
                this.error = {
                    status:err.status,
                    msg:err.bodyText
                }
            })
            const self = this
            setTimeout(()=>{
                self.buttonLoader('runTestButton' , false , self.$trans('Test API'))
            }, 1000)
        },

        buttonLoader(id, isLoader = true, text = 'Install'){
            const e = document.getElementById(id)
            if(isLoader){
                e.innerHTML = `<span class="uk-margin-right" uk-spinner></span>${text}`;
            } else {
                e.innerHTML = `${text}`;
            }
        },

        parentSave(){
            this.$parent.save();
            this.loader = true
            this.load();
        }
    },

    components:{
        IntervalChart,AreaChart,PieChart,GeoChart,ListChart
    }
};
// Framework
import Library from './library'

// Charts Components
import IntervalChart from './charts/intervalChart.vue'
import AreaChart from './charts/areaChart.vue'
import PieChart from './charts/pieChart.vue'
import GeoChart from './charts/geoChart.vue'
import ListChart from './charts/listChart.vue'

export default Analytics
window.Dashboard.components['google-analytics'] = Analytics
</script>

<style scoped>
.tm-form-margin{
    margin-left:10px;
}
</style>