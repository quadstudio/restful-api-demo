<template>
    <div class="container">
        <div class="d-flex flex-row justify-content-between align-items-center">
            <h2>Добавить новость</h2>
            <router-link :to="{name: 'news.index'}" class="btn btn-secondary">Отмена</router-link>
        </div>
        <div class="form-group">
            <label for="title">Заголовок</label>
            <input v-model.trim="item.title"
                   @blur="$v.item.title.$touch()"
                   type="text"
                   class="form-control"
                   :class="{'is-invalid': $v.item.title.$error, 'is-valid': $v.item.title.$dirty && !$v.item.title.$invalid}"
                   id="title"
                   placeholder="Введите заголовок">
            <div class="invalid-feedback" v-if="$v.item.title.$dirty && !$v.item.title.required">
                Заголовок является обязательным
            </div>
        </div>
        <div class="form-group">
            <label for="published_at">Дата</label>
            <Datepicker v-model.trim="item.published_at"
                        @blur="$v.item.published_at.$touch()"
                        :language="ru"
                        :class="{'is-invalid': $v.item.published_at.$error, 'is-valid': $v.item.published_at.$dirty && !$v.item.published_at.$invalid}"
                        format="dd.MM.yyyy"
                        :required="true"
                        :bootstrap-styling="true"
                        id="published_at"
                        placeholder="Выберите дату"></Datepicker>
            <div class="invalid-feedback" v-if="$v.item.published_at.$dirty && !$v.item.published_at.required">
                Дата является обязательной
            </div>
        </div>
        <div class="form-group">
            <label for="annotation">Превью</label>
            <textarea v-model.trim="item.annotation"
                      @blur="$v.item.annotation.$touch()"
                      class="form-control"
                      :class="{'is-invalid': $v.item.annotation.$error, 'is-valid': $v.item.annotation.$dirty && !$v.item.annotation.$invalid}"
                      id="annotation"
                      rows="3"
                      placeholder="Введите превью"></textarea>
            <div class="invalid-feedback" v-if="$v.item.annotation.$dirty && !$v.item.annotation.required">
                Превью является обязательным
            </div>
        </div>
        <div class="form-group">
            <label for="body">Текст</label>
            <textarea v-model.trim="item.body"
                      @blur="$v.item.body.$touch()"
                      class="form-control"
                      :class="{'is-invalid': $v.item.body.$error, 'is-valid': $v.item.body.$dirty && !$v.item.body.$invalid}"
                      id="body"
                      rows="6"
                      placeholder="Введите текст новости"></textarea>
            <div class="invalid-feedback" v-if="$v.item.body.$dirty && !$v.item.body.required">
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

                <img v-if="src" class="mw-100" :src="src"/>
            </div>
        </div>
        <button @click="submit" class="btn btn-primary">Сохранить</button>
    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import {ru} from 'vuejs-datepicker/dist/locale'
    import {required} from 'vuelidate/lib/validators';
    import Dropzone from 'dropzone';

    export default {
        name: "NewsAdd",
        data() {
            return {
                date: new Date(),
                ru: ru,
                dropzone: null,
                src: 'https://dummyimage.com/720x405/aaa/fff',
                item: {
                    title: null,
                    annotation: null,
                    body: null,
                    published_at: null,
                    image: null
                },

            }
        },
        mounted() {
            this.dropzone = new Dropzone(this.$refs.newsImage, this.settings)
        },
        computed: {
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
                        this.item.image = res.data.id;
                        this.src = res.data.links.self;
                    },
                    catch: (e) => {
                        //console.error(e);
                    }

                }
            },
        },
        components: {
            Datepicker
        },
        validations: {
            item: {
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
        },
        methods: {
            async submit() {

                this.$v.$touch()
                if (!this.$v.$invalid) {
                    const attributes = {
                        title: this.item.title,
                        body: this.item.body,
                        annotation: this.item.annotation,
                        published_at: this.formatDate(this.item.published_at),
                        image: this.item.image,
                    };
                    await this.$store.dispatch("storeSingleNews", attributes);
                }
            },
            formatDate(date) {
                let d = new Date(date);
                return d.getFullYear() + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' + ("0" + d.getDate()).slice(-2)
            }
        }
    }
</script>

<style scoped>

</style>