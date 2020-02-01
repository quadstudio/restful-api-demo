<template>
    <div class="media my-4">
        <img :src="item.relationships.image.data.links.self"
             class="align-self-start w-25 mr-3" alt="...">
        <div class="media-body">
            <h5>{{item.attributes.title}}</h5>
            <h6 class="mb-2 text-muted">{{item.attributes.published_at}},
                {{item.relationships.author.data.attributes.name}}</h6>
            <p>{{item.attributes.annotation}}
                <router-link :to="{name: 'news.show', params: { newsId: item.id }}">Подробнее</router-link>
            </p>
        </div>
    </div>
</template>

<script>
    export default {
        name: "NewsIndexComponent",
        props: [
            'item'
        ]
    }
</script>