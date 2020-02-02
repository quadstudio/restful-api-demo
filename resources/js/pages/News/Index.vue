<template>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <router-link :to="{name: 'home'}">Главная</router-link>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Новости</li>
            </ol>
        </nav>
        <div class="d-flex flex-row justify-content-between align-items-center">
            <h2>Новости</h2>
            <template v-if="userStatus === 'success'">
                <router-link :to="{name: 'news.add'}" class="btn btn-secondary">Добавить</router-link>
            </template>
        </div>
        <p v-if="newsStatus === 'loading'">Загружаем новости...</p>
        <template v-else-if="newsStatus === 'success' && news && news.data">
            <template v-if="news.data.length >= 1">
                <IndexComponent
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
    import IndexComponent from '../../components/News/IndexComponent'
    import {mapGetters} from 'vuex'

    export default {
        name: "NewsIndex",
        components: {
            IndexComponent,
        },
        async mounted() {
            await this.$store.dispatch('fetchNews');
            await this.$store.dispatch('fetchProfile');
        },
        computed: {
            ...mapGetters({
                news: 'news',
                newsStatus: 'newsStatus',
                authUser: 'authUser',
                userStatus: 'userStatus',
            })
        }
    }
</script>