

<template>
  <div>
    <work-in-progress></work-in-progress>

    <section class="posts">
      <div class="container">
        <div class="row row-cols-3">
          <div class="col" v-for="post in postsResponse" :key="post.id">
            <div class="product card">
                <img :src="'storage/ + post.cover_image'" :alt="post.title">
                <div class="card-body">
                  <h3>{{post.title}}</h3>
                  <p>{{post.content}}</p>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                   <span v-if="post.category"> <strong>Category:</strong>{{post.category.name}}</span>
                  <div class="tags" v-if="post.tags.length > 0">
                    <ul>
                      <strong>Tags:</strong>
                      <li v-for="tag in post.tags" :key="tag.id">{{tag.name}}</li>
                    </ul>
                  </div>
                </div>
            </div>
            <!-- /.product card -->
          </div>
          <!-- /.col-->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container -->
      <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
          <li class="page-item disabled">
            <a class="page-link" href="#" aria-label="Previous">
              <span aria-hidden="true">&laquo;</span>
              <span class="visually-hidden">Previous</span>
            </a>
          </li>
          <li class="page-item active"><a class="page-link" href="#"></a></li>
          <li class="page-item"><a class="page-link" href="#"></a></li>
          <li class="page-item">
            <a class="page-link" href="#" aria-label="Next">
              <span aria-hidden="true">&raquo;</span>
              <span class="visually-hidden">Next</span>
            </a>
          </li>
        </ul>
      </nav>
    </section>
    <!-- /.posts -->
  </div>
</template>


<script>
import WorkInProgress from "../components/WorkInProgress.vue";
export default {
  name: "App",
  components: { WorkInProgress },
  data() {
    return {
      postsResponse: "",
    };
  },
  methods: {
    getAllPosts() {
      axios
        .get("/api/posts")
        .then((response) => {
          console.log(response);
          this.postsResponse = response.data.data;
        })
        .catch((e) => {
          console.error(e);
        });
    },
  },
  mounted() {
    this.getAllPosts();
  }
};
</script>