<template>
    <div>
        <ul class="uk-list uk-list-divider uk-margin-medium-top">
            <li v-for="(dt , id) in orderData" :key="id">
                <span class="uk-text-bold">{{dt.title}}</span>
                <span class="uk-align-right">{{dt.value}}</span>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        props:['report'],

        chart:{
            label: 'List'
        },

        data(){
            return {
                data:[],
                chartID: 'container-'
            }
        },

        created(){
            for (const key in this.report.data) {
                if (this.report.data.hasOwnProperty(key)) {
                    let element = this.report.data[key];
                    this.data.push({title: element.title, value: parseInt(element.value)})
                }
            }
        },

        computed:{
            orderData(){
                return _.orderBy(this.data , 'value' , 'desc')
            }
        },

        methods: {
            randomName(length) {
                var result           = '';
                var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                var charactersLength = characters.length;
                for ( var i = 0; i < length; i++ ) {
                    result += characters.charAt(Math.floor(Math.random() * charactersLength));
                }
                return result;
            }
        }
    }
</script>