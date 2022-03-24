<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="col-md-6 offset-2 mt-5">
                    <form @submit.prevent="shortenUrl">
                        <div class="form-group">
                            <label for="url">URL</label>
                            <input type="text" class="form-control" id="url" v-model="url">
                        </div>
                        <br/>
                        <button type="submit" class="btn btn-primary">Shorten</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            url: ""
        }
    },
    methods: {
        shortenUrl() {
            this.axios
                .post('http://localhost:8000/api/shorten-url', {url:this.url})
                .then(response => {
                    console.log(response.data);
                })
                .catch(err => {
                    console.log(err);
                })
        }
    }
}
</script>
