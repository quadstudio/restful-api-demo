<template>
    <div class="container">
        <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
            <div class="col-md-8 p-lg-8 mx-auto my-5">
                <h1 class="display-4 font-weight-normal">404</h1>
                <p class="lead font-weight-normal">Страница не найдена</p>
                <router-link class="btn btn-outline-secondary" :to="{name: 'news.index'}">К новостям</router-link>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "NotFound"
    }
</script>

<style scoped>

</style>