<template>
  <div class="container">
    <div class="mb-2"><a class="btn btn-dark" href="/">Articles</a></div>
    <div class="content col-md-12" v-if="article.id">
      <img :src="article.image" alt="" class="img-fluid">
      <h3 class="mt-2">{{ article.title }}</h3>
      <h5><span v-if="deleted" class="text-danger">(DELETED)</span></h5>
      <ul class="post-meta list-inline">
        <li class="list-inline-item" v-if="article.author && article.author.name">
          <i class="fa fa-user-circle-o"></i> <a href="#">{{ article.author.name }}</a>
        </li>
        <li class="list-inline-item" v-if="article.created_at">
          <i class="fa fa-calendar-o"></i> <a href="#">{{ article.created_at }}</a>
        </li>
      </ul>
      <p v-if="article.content">
        {{ article.content }}
      </p><br>
    </div>
  </div>
</template>

<style>
body {
  margin-top: 20px;
  background: #f3f3f3;
}

/*
Timeline
*/

.content {
  background: #fff;
  border: 1px solid #e6e6e6;
  padding: 35px 20px;
  border-radius: 3px;
  text-align: left;

  -webkit-box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.2), 0 6px 10px 0 rgba(0, 0, 0, 0.3);
  box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.2), 0 6px 10px 0 rgba(0, 0, 0, 0.3);
}

.vtimeline-content h3 {
  font-size: 1.5em;
  font-weight: 600;
  display: inline-block;
  margin: 0;
}

.vtimeline-content p {
  font-size: 0.9em;
  margin: 0;
}

.vtimeline-icon i {
  display: block;
  font-size: 1.5em;
  line-height: 50px;

}

.post-meta {
  padding-top: 15px;
  margin-bottom: 20px;
}

.post-meta li:not(:last-child) {
  margin-right: 10px;
}

h3 {
  font-family: "Montserrat", sans-serif;
  color: #252525;
  font-weight: 700;
  font-variant-ligatures: common-ligatures;
  margin-top: 0;
  letter-spacing: -0.2px;
  line-height: 1.3;
  text-decoration: none;
}

</style>

<script>
import gql from "graphql-tag";

const articleFields = gql`fragment ArticleFields on Article {
    id
    title
    image
    content
    created_at
    author {
        name
        email
    }
}`;

export default {
  name: 'article-page',
  mounted() {
  },
  props: ['article_id'],
  data() {
    return {
      article: {},
      page: 1,
      deleted: false
    }
  },
  apollo: {
    article: {
      query: gql`query GetArticle($id: ID!) {
                  article(id: $id) {
                     ...ArticleFields
                  }
                }
                ${articleFields}
            `,
      variables() {
        return {
          id: this.article_id
        }
      },
      result({data}) {
        this.article = data.article;
      },
    },
    $subscribe: {
      articleUpdated: {
        query: gql`
                  subscription articleUpdated {
                    articleUpdated {
                        ...ArticleFields
                    }
                  }
                  ${articleFields}
                `,
        result({data}) {
          this.article = data.articleUpdated;
        },
      },
      articleDeleted: {
        query: gql`
                  subscription articleDeleted {
                    articleDeleted {
                        id
                    }
                  }
                `,
        result({data}) {
          if (data.articleDeleted.id == this.article.id) {
            this.deleted = true;
          }
        },
      }
    },
  },
}
</script>
