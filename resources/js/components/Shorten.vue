<template>
    <div class="card">
        <div class="card-header bg-primary">Generate Short URL</div>
        <div class="card-body">
            <form @submit.prevent="shortenUrl">
                <div class="form-group">
                    <label for="url">URL</label>
                    <input type="text" class="form-control" id="url" v-model="url" required placeholder="Enter URL to generate short URL">
                </div>
                <div class="form-group">
                    <label for="sub_domain">Sub Domain (Optional)</label>
                    <input type="text" class="form-control" id="sub_domain" v-model="sub_domain" placeholder="Optional URL subdomain">
                </div>
                <br/>
                <button type="submit" class="btn btn-primary">Shorten</button>
            </form>
        </div>
        <div class="card-footer">
            <div v-if="error_message" class="mt-2">
                <label class="text-danger">{{ error_message }}</label>
            </div>
            <div v-if="success_message" class="mt-2">
                <div class="text-success" v-html="success_message"></div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Shorten",
    data() {
        return {
            error_message: "",
            success_message: "",
            url: "",
            sub_domain: ""
        }
    },
    methods: {
        shortenUrl() {
            this.success_message = this.error_message = "";
            this.sub_domain = this.sub_domain.replace(/\//g, "");
            this.axios
                .post('http://localhost:8000/api/shorten-url', {url:this.url, sub_domain:this.sub_domain})
                .then(response => {
                    let {data} = response;
                    console.log(data);

                    if(data.code !== 200){
                        this.error_message = data.message;
                        return;
                    }

                    this.success_message = data.message+`<br/> Generated URL: <a href=${data.data.fullUrl} target='_blank'>${data.data.shortUrl}</a>`;
                })
                .catch(err => {
                    console.log(err);
                })
        },
    }
}
</script>

<style scoped>

</style>
