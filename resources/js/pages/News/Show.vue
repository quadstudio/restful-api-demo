<template>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <router-link :to="{name: 'home'}">Главная</router-link>
                </li>
                <li class="breadcrumb-item">
                    <router-link :to="{name: 'news.index'}">Новости</router-link>
                </li>
                <li v-if="newsIsLoaded && item" class="breadcrumb-item active" aria-current="page">{{item.attributes.title}}</li>
            </ol>
        </nav>
        <div class="d-flex flex-row justify-content-between align-items-center">
            <h2>Новости</h2>
            <template v-if="newsIsLoaded && authUser && item && authUser.id === item.relationships.author.data.id">
                <router-link :to="{name: 'news.edit', params: {newsId: item.id}}" class="btn btn-secondary">
                    Изменить
                </router-link>
            </template>
        </div>
        <p v-if="newsStatus === 'loading'">Загружаем новость...</p>
        <template v-else-if="newsIsLoaded && item">
            <div class="card mt-3">

                <div class="card-body">
                    <h5 class="card-title">{{item.attributes.title}}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">
                        {{item.attributes.published_at_ru}},
                        <router-link exact
                                     :to="{name: 'authors.show', params: {authorId: item.relationships.author.data.id}}">
                            {{item.relationships.author.data.attributes.name}}
                        </router-link>
                    </h6>
                    <img :src="item.relationships.image.data.links.self" class="mw-100 my-2" alt=""/>
                    <p class="card-text">{{item.attributes.body}}</p>
                </div>
            </div>
        </template>
        <p v-else-if="newsStatus === 'error'">
            error
        </p>
    </div>
</template>

<script>

    import {mapGetters} from 'vuex'

    export default {
        name: "NewsShow",
        methods: {
            newsIsLoaded() {
                return this.newsStatus === 'success';
            }
        },
        async mounted() {
            await this.$store.dispatch('fetchSingleNews', this.$route.params.newsId);
            await this.$store.dispatch('fetchProfile')
        },
        computed: {
            ...mapGetters({
                item: 'newsShow',
                newsStatus: 'newsStatus',
                authUser: 'authUser'
            })
        }
    }
</script>

<style scoped>

</style>