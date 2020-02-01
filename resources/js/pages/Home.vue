<template>
    <div class="container">
        <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
            <div class="col-md-8 p-lg-8 mx-auto my-5">
                <h1 class="display-4 font-weight-normal">RESTful API</h1>
                <p class="lead font-weight-normal">Веб приложение «Новостной портал» с использованием определенных технологий в зависимости от желаемой позиции.</p>
                <router-link class="btn btn-outline-secondary" :to="{name: 'news.index'}">К новостям</router-link>
            </div>
            <div class="product-device box-shadow d-none d-md-block"></div>
            <div class="product-device product-device-2 box-shadow d-none d-md-block"></div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Home"
    }
</script>

<style scoped>

</style>