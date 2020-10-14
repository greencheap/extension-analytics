<template>
    <div>
        <div v-if="editing" class="uk-card-header pk-panel-teaser">
            <div v-if="config.view_id">
                <button class="uk-button uk-button-small uk-button-primary" @click.prevent="openEditing">{{ 'Open The Settings' | trans }}</button>
            </div>
        </div>

        <div class="uk-card-body">
            <div v-if="loader" class="uk-text-center">
                <v-loader />
            </div>

            <div class="uk-margin-top uk-text-center" v-if="!config.view_id">
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
                        <a href="https://support.google.com/analytics/answer/1009694?hl=tr" target="_blank">{{ 'How to add Account' | trans }}</a>
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
        description() {

        },
        defaults: {
            analytics:{
                startDate: '1daysAgo',
                endDate: 'today',
                expression: 'ga:sessions',
                alias: 'sessions'
            }
        },
    },

    replace: false,

    props: ['widget', 'editing'],

    data() {
        return {
            loader:false,
            config:window.$analytics,
            modalConfigure:{
                view_id: ''
            },
            error:{
                status: '',
                msg:''
            }
        };
    },

    created(){
        if(this.config.view_id){
            this.loader = true
            this.load()
        }
    },

    methods:{
        load(){
            console.log(this.widget)
            this.$http.get('admin/api/analytics/getreport' , {
                params: {
                    view_id: this.config.view_id,
                    analytics: this.widget.analytics
                }
            }).then((res) => {
                console.log(res)
            }).catch((err) => {
                console.log(err)
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
        }
    }
};

export default Analytics
window.Dashboard.components['google-analytics'] = Analytics
</script>

<style scoped>
.tm-form-margin{
    margin-left:10px;
}
</style>