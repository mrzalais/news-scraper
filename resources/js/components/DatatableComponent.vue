<template>
    <div>
        <div class="d-flex justify-content-between align-content-center mb-2">
            <div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>Title</th>
                            <th>Site</th>
                            <th>Score</th>
                            <th>Author</th>
                            <th>Created</th>
                            <th>Comments</th>
                        </tr>
                        <tr v-for="post in rows.data" :key="post.id">
                            <td>{{ post.title }}</td>
                            <td>{{ post.site }}</td>
                            <td>{{ post.score }}</td>
                            <td>{{ post.author }}</td>
                            <td>{{ post.created }}</td>
                            <td>{{ post.comments }}</td>
                            <td>
                                <button
                                    onclick="confirm('Are you sure you wanna delete this Record?')"
                                    class="btn btn-danger btn-sm"
                                    @click="deletePost(post.id)"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row mt-4">
                    <div class="col-sm-6 offset-5">
                        <pagination :data="rows" @pagination-change-page="getPosts"></pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { Bootstrap5Pagination } from 'laravel-vue-pagination';

export default {
    data() {
        return {
            columns: [
                {
                    label: 'title',
                    field: 'title'
                },
                {
                    label: 'site',
                    field: 'site'
                },
                {
                    label: 'score',
                    field: 'score'
                },
                {
                    label: 'author',
                    field: 'author'
                },
                {
                    label: 'created',
                    field: 'created'
                },
                {
                    label: 'comments',
                    field: 'comments'
                },
            ],
            rows: [],
            page: 1,
            filter: '',
            perPage: 10,
        }
    },
    methods: {
        getPosts: function (page = 1) {
            axios.get('/posts?page=' + page).then(function (response) {
                this.rows = response.data;
            }.bind(this));
        },
        deletePost: function (postId) {
            console.log(postId)
            axios.delete('/api/posts/delete/' + postId).then(response => {
                this.getPosts();
            });
        }
    },
    created: function () {
        this.getPosts()
    }
}
</script>
