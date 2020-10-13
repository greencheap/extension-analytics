<template>
    <div>
        <div v-if="editing" class="uk-card-header pk-panel-teaser">
            Hello World Edit
        </div>

        <div class="uk-card-body">
            <div v-if="loader && config.access_token.length" class="uk-text-center">
                <v-loader />
            </div>
            <div v-if="!loader && config.access_token.length">
                Hello World
            </div>
            <div class="uk-margin-top uk-text-center" v-if="!config.access_token.length">
                <p>{{'The widget information is not configured correctly. Please update the settings.'}}</p>
                <a class="uk-text-small" :href="$url('admin/system/package/extensions')">{{ 'Go to setting of Yandex Metrica' | trans }}</a>
            </div>
        </div>
    </div>
</template>

<script>

const Metrica = {

    name: 'metrica',

    type: {
        id: 'metrica',
        label: 'Yandex Metrica',
        description() {

        }
    },

    replace: false,

    props: ['widget', 'editing'],

    data() {
        return {
            loader:false,
            config:window.$metrica
        };
    }
};

export default Metrica
window.Dashboard.components['yandex-metrica'] = Metrica
</script>
