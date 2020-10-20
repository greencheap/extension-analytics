<template>
    <div :id="this.chartID"></div>
</template>

<script>
    import DataSet from '@antv/data-set';
    import { Chart } from '@antv/g2';
    const country = require('../../../country.json')
    export default {
        props:['report'],

        chart:{
            label: 'Geo'
        },

        data(){
            return {
                data:[],
                chart: false,
                options:{
                    height: 200
                },
                chartID: 'container-',
                country: country
            }
        },

        created(){
            this.chartID = 'container-'+this.randomName(20);
            for (const key in this.report.data) {
                if (this.report.data.hasOwnProperty(key)) {
                    let element = this.report.data[key];
                    this.data.push({name: element.title, value: parseInt(element.value)})
                }
            }
        },

        mounted() {
            this.chart = new Chart(_.merge({container:this.chartID,autoFit: true} , this.options));

            const ds = new DataSet();
            const dv = ds.createView('back').source(this.country, {
                type: 'GeoJSON',
            });

            const userDv = ds
            .createView()
            .source(this.data)
            .transform({
                geoDataView: dv,
                field: 'name',
                type: 'geo.centroid',
                as: ['longitude', 'latitude'],
            });

            this.chart.scale({
                longitude: {
                    sync: true,
                },
                latitude: {
                    sync: true,
                },
            });
            this.chart.axis(false);

            this.chart.legend({ position: 'right' });

            this.chart.tooltip({
                showTitle: false,
                showMarkers: false,
            });

            const bgView = this.chart.createView();
            bgView.data(dv.rows);
            bgView.tooltip(false);
            bgView
            .polygon()
            .position('longitude*latitude')
            .color('#ebedf0')
            .style({
                lineWidth: 1,
                stroke: '#fafbfc',
            });

            const userView = this.chart.createView();
            userView.data(userDv.rows);
            userView
            .point()
            .position('longitude*latitude')
            .color('#66D092')
            .shape('circle')
            .size('value', [5, 15])
            .style({
                lineWidth: 1,
                stroke: '#66D092',
            });
            userView.interaction('element-active');
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