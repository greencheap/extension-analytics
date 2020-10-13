<template>
    <form class="uk-margin-large" @submit.prevent="save">
        <div class="uk-modal-header">
            <h1 class="uk-h3">{{ 'Metrica Settings' | trans }}</h1>
        </div>
        <div class="uk-modal-body">
            <div class="uk-margin">
                <label class="uk-form-label">{{ 'ID' | trans}}</label>
                <div class="uk-form-controls">
                    <input type="text" class="uk-input" v-model="config.id" :disabled="config.access_token.length">
                </div>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label">{{ 'Password' | trans}}</label>
                <div class="uk-form-controls">
                    <input type="text" class="uk-input" v-model="config.password" :disabled="config.access_token.length">
                </div>
            </div>
            <div class="uk-margin">
                <a :href="getAccessTokenUri" class="uk-button uk-button-primary uk-width-expand" target="_blank" v-show="!config.access_token.length && config.id.length && config.password.length">{{'Get Access Token' | trans}}</a>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label">{{ 'Access Token' | trans}}</label>
                <div class="uk-form-controls">
                    <input type="text" class="uk-input" v-model="config.access_token" :disabled="!config.id.length || !config.password.length">
                </div>
            </div>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button class="uk-button uk-button-primary" type="submit">{{ 'Save' | trans }}</button>
        </div>
    </form>
</template>

<script>

    module.exports = {
        name: 'metrica',

        settings: true,

        props: ['package'],

        data(){
            return {
                config: this.package.config
            }
        },

        computed:{
            getAccessTokenUri(){
                return `https://oauth.yandex.com/authorize?response_type=token&client_id=${this.config.id}`
            }
        },

        methods: {
            save() {
                this.$http.post('admin/system/settings/config', { name: 'metrica', config: this.config }).then(function () {
                   this.$notify('Settings saved.', '');
                }).catch(function (response) {
                   this.$notify(response.message, 'danger');
                }).finally(function () {
                   this.$parent.close();
                });
            }
        }
    };

    window.Extensions.components['metrica-settings'] = module.exports;
</script>
