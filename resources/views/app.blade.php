<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" value="{{ csrf_token() }}" />
    <title>Vue JS CRUD Operations in Laravel</title>
    <link href="{{ mix('css/app.css') }}" type="text/css" rel="stylesheet" />
</head>
<body>
<div id="app">
    <div class="col-md-6 offset-2 mt-5">
        <form>
            <div class="form-group">
                <label for="url">URL</label>
                <input type="text" class="form-control" v-model="url">
            </div>
            <br/>
            <button type="submit" class="btn btn-primary">Shorten</button>
        </form>
    </div>
</div>
<script src="{{ mix('js/app.js') }}" type="text/javascript"></script>

<script>
    export default {
        data() {
            return {
                product: {}
            }
        },
        methods: {
            shortenUrl() {
                this.axios
                    .post('http://localhost:8000/api/shorten-url', this.product)
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
</body>
</html>
