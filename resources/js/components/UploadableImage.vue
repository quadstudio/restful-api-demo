<template>
    <img :src="newsImage.data.links.self"
         ref="newsImage"
         :class="classes"
         alt="">
</template>

<script>
    import Dropzone from 'dropzone';

    export default {
        name: "UploadableImage",
        props: [
            'width',
            'height',
            'storage',
            'newsImage',
            'classes'
        ],
        data() {
            return {
                dropzone: null,
                uploadedImage: null
            }
        },
        mounted() {
            this.dropzone = new Dropzone(this.$refs.newsImage, this.settings);
        },
        computed: {
            settings() {
                return {
                    paramName: 'data[attributes][image]',
                    url: '/api/v1/images',
                    acceptedFiles: 'image/*',
                    params: {
                        'data[attributes][width]': this.width,
                        'data[attributes][height]': this.height,
                        'data[attributes][storage]': this.storage,
                    },
                    headers: {
                        'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content
                    },
                    success: (e, res) => {
                        //this.uploadedImage = res;
                        //this.$store.dispatch('fetchProfile')
                        //this.$store.dispatch('fetchUser', this.$route.params.userId);
                        //this.$store.dispatch('fetchUserPosts', this.$route.params.userId);
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>