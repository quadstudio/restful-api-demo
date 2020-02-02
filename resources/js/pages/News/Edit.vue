<template>
    <div class="container">
        <div class="d-flex flex-row justify-content-between align-items-center">
            <h2>Изменить новость</h2>
            <template v-if="newsStatus === 'success'">
                <router-link :to="{name: 'news.show', params: {newsId: item.id}}" class="btn btn-secondary">Отмена</router-link>
            </template>
        </div>
        <p v-if="newsStatus === 'loading'">Загружаем новость...</p>
        <template v-else-if="newsStatus === 'success'">
            <div class="form-group">
                <label for="title">Заголовок</label>
                <input v-model.trim="item.attributes.title"
                       @blur="$v.item.attributes.title.$touch()"
                       type="text"
                       class="form-control"
                       :class="{'is-invalid': $v.item.attributes.title.$error, 'is-valid': $v.item.attributes.title.$dirty && !$v.item.attributes.title.$invalid}"
                       id="title"
                       placeholder="Введите заголовок">
                <div class="invalid-feedback" v-if="$v.item.attributes.title.$dirty && !$v.item.attributes.title.required">
                    Заголовок является обязательным
                </div>
            </div>
            <div class="form-group">
                <label for="published_at">Дата</label>
                <Datepicker v-model.trim="item.attributes.published_at"
                            @blur="$v.item.attributes.published_at.$touch()"
                            :language="ru"
                            :class="{'is-invalid': $v.item.attributes.published_at.$error, 'is-valid': $v.item.attributes.published_at.$dirty && !$v.item.attributes.published_at.$invalid}"
                            format="dd.MM.yyyy"
                            :required="true"
                            :clear-button="true"
                            :bootstrap-styling="true"
                            id="published_at"
                            placeholder="Выберите дату"></Datepicker>
                <div class="invalid-feedback" v-if="$v.item.attributes.published_at.$dirty && !$v.item.attributes.published_at.required">
                    Дата является обязательной
                </div>
            </div>
            <div class="form-group">
                <label for="annotation">Превью</label>
                <textarea v-model.trim="item.attributes.annotation"
                          @blur="$v.item.attributes.annotation.$touch()"
                          class="form-control"
                          :class="{'is-invalid': $v.item.attributes.annotation.$error, 'is-valid': $v.item.attributes.annotation.$dirty && !$v.item.attributes.annotation.$invalid}"
                          id="annotation"
                          rows="3"
                          placeholder="Введите превью"></textarea>
                <div class="invalid-feedback" v-if="$v.item.attributes.annotation.$dirty && !$v.item.attributes.annotation.required">
                    Превью является обязательным
                </div>
            </div>
            <div class="form-group">
                <label for="body">Текст</label>
                <textarea v-model.trim="item.attributes.body"
                          @blur="$v.item.attributes.body.$touch()"
                          class="form-control"
                          :class="{'is-invalid': $v.item.attributes.body.$error, 'is-valid': $v.item.attributes.body.$dirty && !$v.item.attributes.body.$invalid}"
                          id="body"
                          rows="6"
                          placeholder="Введите текст новости"></textarea>
                <div class="invalid-feedback" v-if="$v.item.attributes.body.$dirty && !$v.item.attributes.body.required">
                    Превью является обязательным
                </div>
            </div>
            <div class="form-group">
                <label for="body">Изображение</label>
                <div>
                    <div class="border">
                        <svg ref="newsImage"
                             xmlns="http://www.w3.org/2000/svg"
                             style="width: 50px;"
                             class="dz-clickable"
                             viewBox="0 0 24 24">
                            <path d="M21.8 4H2.2c-.2 0-.3.2-.3.3v15.3c0 .3.1.4.3.4h19.6c.2 0 .3-.1.3-.3V4.3c0-.1-.1-.3-.3-.3zm-1.6 13.4l-4.4-4.6c0-.1-.1-.1-.2 0l-3.1 2.7-3.9-4.8h-.1s-.1 0-.1.1L3.8 17V6h16.4v11.4zm-4.9-6.8c.9 0 1.6-.7 1.6-1.6 0-.9-.7-1.6-1.6-1.6-.9 0-1.6.7-1.6 1.6.1.9.8 1.6 1.6 1.6z"/>
                        </svg>
                        <small class="text-muted">Кликните по пиктограмме для выбор изображения</small>
                    </div>

                    <img class="mw-100" :src="item.relationships.image.data.links.self"/>
                </div>
            </div>
            <button @click="submit" class="btn btn-primary">Сохранить</button>
        </template>
        <p v-else-if="newsStatus === 'error'">
            error
        </p>

    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import {ru} from 'vuejs-datepicker/dist/locale'
    import {required} from 'vuelidate/lib/validators';
    import {mapGetters} from 'vuex'
    import Dropzone from 'dropzone';
    export default {
        name: "NewsEdit",
        data() {
            return {
                ru,
                dropzone: null,
                src: null
            }
        },
        components: {
            Datepicker
        },
        validations: {
            item: {
                attributes: {
                    title: {
                        required,
                    },
                    annotation: {
                        required,
                    },
                    body: {
                        required,
                    },
                    published_at: {
                        required,
                    },
                }
            }
        },
        async mounted() {
            await this.$store.dispatch('fetchSingleNews', this.$route.params.newsId);
            this.dropzone = new Dropzone(this.$refs.newsImage, this.settings)
        },
        computed: {
            ...mapGetters({
                item: 'newsShow',
                newsStatus: 'newsStatus'
            }),
            settings() {
                return {
                    paramName: 'data[attributes][image]',
                    url: '/api/v1/images',
                    acceptedFiles: 'image/*',
                    params: {
                        'data[attributes][width]': 720,
                        'data[attributes][height]': 405,
                        'data[attributes][storage]': 'news',
                    },
                    clickable: '.dz-clickable',
                    headers: {
                        'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content
                    },
                    success: (e, res) => {
                        this.item.relationships.image = res;
                    },
                    catch: (e) => {
                        //console.error(e);
                    }

                }
            }
        },
        methods: {
            async submit() {
                this.$v.$touch()
                if (!this.$v.$invalid) {
                    let date = new Date(this.item.attributes.published_at);
                    const attributes = {
                        title: this.item.attributes.title,
                        body: this.item.attributes.body,
                        annotation: this.item.attributes.annotation,
                        published_at: this.formatDate(this.item.attributes.published_at),
                        image: this.item.relationships.image.data.id
                    };
                    await this.$store.dispatch("updateNews", {
                        newsId: this.item.id,
                        attributes
                    });
                }
            },
            formatDate(date) {
                let d = new Date(date);
                return d.getFullYear() + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' + ("0" + d.getDate()).slice(-2)
            }
        }
    }
</script>