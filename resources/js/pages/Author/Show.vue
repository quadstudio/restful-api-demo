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
                <li v-if="authorIsLoaded && author" class="breadcrumb-item active" aria-current="page">От автора
                    {{author.data.attributes.name}}
                </li>
            </ol>
        </nav>
        <template v-if="authorIsLoaded && author">
            <h2>Новости от автора {{author.data.attributes.name}}</h2>
        </template>
        <p v-if="newsStatus === 'loading'">Загружаем новости...</p>
        <template v-else-if="newsStatus === 'success' && news && news.data">
            <template v-if="news.data.length >= 1">
                <IndexComponent
                        v-if="news.data.length >= 1"
                        :key="index"
                        v-for="(item, index) in news.data"
                        :item="item"/>
            </template>
            <p v-else>
                Новости не найдены...
            </p>
        </template>
        <p v-else-if="newsStatus === 'error'">
            Произошла ошибка...
        </p>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import IndexComponent from '../../components/News/IndexComponent';

    export default {
        name: "AuthorShow",
        components: {
            IndexComponent,
        },
        methods: {
            authorIsLoaded() {
                return authorStatus.author === 'success';
            }
        },
        mounted() {
            this.$store.dispatch('fetchAuthor', this.$route.params.authorId);
            this.$store.dispatch('fetchAuthorNews', this.$route.params.authorId);
        },
        computed: {
            ...mapGetters({
                author: 'author',
                news: 'news',
                authorStatus: 'authorStatus',
                newsStatus: 'newsStatus',
            })
        }
    }
</script>

<style scoped>

</style>