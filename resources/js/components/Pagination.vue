<template>
    <nav aria-label="News navigation">
        <ul class="pagination">
            <router-link tag="li" :to="links.prev" class="page-item">
                <a class="page-link">Предыдущая</a>
            </router-link>
            <router-link tag="li" :to="links.next" class="page-item">
                <a class="page-link">Следующая</a>
            </router-link>
        </ul>
    </nav>

</template>

<script>
    export default {
        name: "Pagination",
        props: [
            'links',
            'meta'
        ]
    }
</script>

<style scoped>

</style>