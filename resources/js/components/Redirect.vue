<template>
    <div class="card">
        <div class="card-header bg-info">Redirect with Short URL</div>
        <div class="card-body">
            <form @submit.prevent="redirectToUrl">
                <div class="form-group">
                    <label for="short_url">Short URL</label>
                    <input type="text" class="form-control" id="short_url" v-model="short_url" required placeholder="Enter Generated short url to redirect">
                </div>
                <br/>
                <button type="submit" class="btn bg-info">Redirect</button>
            </form>
        </div>
        <div class="card-footer">
            <div v-if="error_message_red" class="mt-2">
                <label class="text-danger">{{ error_message_red }}</label>
            </div>
            <div v-if="success_message_red" class="mt-2">
                <div class="text-success" v-html="success_message_red"></div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Redirect",
    data() {
        return {
            error_message_red: "",
            success_message_red: "",
            short_url: "",
        }
    },
    methods: {
        redirectToUrl() {
            this.success_message_red = this.error_message_red = "";
            this.axios
                .post('http://localhost:8000/api/full-url', {short_url: this.short_url})
                .then(response => {
                    let {data} = response;
                    console.log(data);

                    if(data.code !== 200){
                        this.error_message_red = data.message;
                        return;
                    }

                    this.success_message_red = data.message;

                    setTimeout(function(){
                        window.open(data.data.fullUrl, "_blank");
                    }, 1000);
                })
                .catch(err => {
                    console.log(err);
                })
        }
    }
}
</script>

<style scoped>

</style>
