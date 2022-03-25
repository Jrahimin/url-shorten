<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 mt-5">
                <div class="row">
                    <div class="col-md-6">
                        <shorten></shorten>
                    </div>
                    <div class="col-md-4">
                        <redirect></redirect>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Redirect from "./Redirect";
import Shorten from "./Shorten";
export default {
    components: {Shorten, Redirect},
}
</script>
