<template>
    <div :id="this.chartID"></div>
</template>

<script>
    import { Chart } from '@antv/g2';

    export default {
        props:['report'],

        chart:{
            label: 'Pie'
        },

        data(){
            return {
                data:[],
                chart: false,
                options:{
                    height: 300
                },
                chartID: 'container-'
            }
        },

        created(){
            this.chartID = 'container-'+this.randomName(20);
            for (const key in this.report.data) {
                if (this.report.data.hasOwnProperty(key)) {
                    let element = this.report.data[key];
                    this.data.push({title: element.title, value: parseInt(element.value)})
                }
            }
        },

        mounted() {
            this.chart = new Chart(_.merge({container:this.chartID,autoFit: true} , this.options));

            this.chart.coordinate('theta', {
                radius: 0.75,
            });

            this.chart.data(this.data)
            //this.chart.area().position('title*value');
            this.chart.tooltip({
                showTitle: false,
                showMarkers: false,
            });

            this.chart.interval()
            .adjust('stack')
            .position('value')
            .color('title')
            .label('title');

            this.chart.interaction('element-active');
            this.chart.render();
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