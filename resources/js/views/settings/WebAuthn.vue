<template>
    <div>
        <setting-tabs :activeTab="'settings.webauthn'"></setting-tabs>
        <div class="options-tabs">
            <div class="columns is-centered">
                <div class="form-column column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-third-fullhd">
                    <h4 class="title is-4 has-text-grey-light">{{ $t('settings.personal_access_tokens') }}</h4>
                    <div class="is-size-7-mobile">
                        {{ $t('settings.token_legend')}}
                    </div>
                    <div class="mt-3 mb-6">
                        <label class="button is-link is-medium is-rounded is-focused" @click="register()">
                            register
                        </label>
                    </div>
                    <div class="mt-3 mb-6">
                        <label class="button is-link is-medium is-rounded is-focused" @click="login()">
                            login
                        </label>
                    </div>
                    <!-- <div v-if="tokens.length > 0">
                        <div v-for="token in tokens" :key="token.id" class="group-item has-text-light is-size-5 is-size-6-mobile">
                            <font-awesome-icon v-if="token.value" class="has-text-success" :icon="['fas', 'check']" /> {{ token.name }}
                            <div class="tags is-pulled-right">
                                <a v-if="token.value" class="tag" v-clipboard="() => token.value" v-clipboard:success="clipboardSuccessHandler">{{ $t('commons.copy') }}</a>
                                <a class="tag is-dark " @click="revokeToken(token.id)">{{ $t('settings.revoke') }}</a>
                            </div>
                            <span v-if="token.value" class="is-size-7-mobile is-size-6 my-3">
                                {{ $t('settings.make_sure_copy_token') }}
                            </span>
                            <span v-if="token.value" class="pat is-family-monospace is-size-6 is-size-7-mobile has-text-success">
                                {{ token.value }}
                            </span>
                        </div>
                    </div> -->
                    <!-- <div v-if="isFetching && tokens.length === 0" class="has-text-centered">
                        <span class="is-size-4">
                            <font-awesome-icon :icon="['fas', 'spinner']" spin />
                        </span>
                    </div> -->
                    <!-- footer -->
                    <vue-footer :showButtons="true">
                        <!-- close button -->
                        <p class="control">
                            <router-link :to="{ name: 'accounts', params: { toRefresh: false } }" class="button is-dark is-rounded">{{ $t('commons.close') }}</router-link>
                        </p>
                    </vue-footer>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import Form from './../../components/Form'

    export default {
        data(){
            return {
                // isFetching: false,
                form: new Form({
                    token : '',
                })
            }
        },

        mounted() {
            // this.fetchTokens()
        },

        methods : {

            /**
             * Get a challenge
             */
            async register() {
                // Check browser supports
                // if ('credentials' in navigator) {
                //     // do webauthn things
                // }
                // else {
                //     // do traditional auth things
                // }

                // check https env
                if (!window.isSecureContext) {
                    console.log('https only')
                    return false
                }

                if (!window.PublicKeyCredential) {
                    throw new Error("Unable to access credentials interface");
                    return false
                }

                const registerOptions = await this.axios.post('/webauthn/register/options').then(res => res.data)
                console.log('registerOptions')
                console.log(registerOptions)
                const publicKey = this.parseIncomingServerOptions(registerOptions)

                // try {
                    const bufferedCredentials = await navigator.credentials.create({
                        publicKey
                    })
                    console.log('bufferedCredentials')
                    console.log(bufferedCredentials)
                // }
                // catch (error) {
                    // console.log(error)
                    // console.log(error.name)
                    // if (error.name == 'AbortError')
                    // if (error.name == 'NotAllowedError')
                    // InvalidStateError, NotAllowedError : la clé ne permet pas de faire ce qu'on lui demande, ex: ne gère pas le PIN dans Firefox
                    // alert(error.name)
                    // return false
                // }

                const publicKeyCredential = this.parseOutgoingCredentials(bufferedCredentials);
                    console.log('publicKeyCredential')
                    console.log(publicKeyCredential)

                // return false

                this.axios.post('/webauthn/register', publicKeyCredential).then(response => {
                    console.log(response.data)
                })
                .catch(error => {
                    console.log(error.response)
                });
            },

            // clipboardSuccessHandler ({ value, event }) {

            //     this.$notify({ type: 'is-success', text: this.$t('commons.copied_to_clipboard') })
            // },

            // clipboardErrorHandler ({ value, event }) {
            //     console.log('error', value)
            // },

            // /**
            //  * revoke a token (after confirmation)
            //  */
            // async revokeToken(tokenId) {
            //     if(confirm(this.$t('settings.confirm.revoke'))) {

            //         await this.axios.delete('/api/v1/oauth/personal-access-tokens/' + tokenId).then(response => {
            //             // Remove the revoked token from the collection
            //             this.tokens = this.tokens.filter(a => a.id !== tokenId)
            //             this.$notify({ type: 'is-success', text: this.$t('settings.token_revoked') })
            //         });
            //     }
            // }
        },
    }
</script>