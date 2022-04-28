<template>
    <div class="container">
        <div class="page-timeline" :class="article.created_at ? 'page-timeline-line page-timeline-after' : ''"
             v-for="article in articles.data" :key="article.id">
            <div :class="article.created_at ? 'vtimeline-point' : ''">
                <div class="vtimeline-icon" v-if="article.created_at">
                    <i class="fa fa-image"></i>
                </div>
                <div class="vtimeline-block">
                    <span class="vtimeline-date" v-if="article.created_at">{{ article.created_at }}</span>
                    <div class="vtimeline-content">
                        <a href="#" v-if="article.image"><img :src="article.image" alt="" class="img-fluid mb20"></a>
                        <a href="#" v-if="article.title"><h3>{{ article.title }}</h3></a>
                        <ul class="post-meta list-inline">
                            <li class="list-inline-item" v-if="article.author && article.author.name">
                                <i class="fa fa-user-circle-o"></i> <a href="#">{{ article.author.name }}</a>
                            </li>
                            <li class="list-inline-item" v-if="article.created_at">
                                <i class="fa fa-calendar-o"></i> <a href="#">{{ article.created_at }}</a>
                            </li>
                        </ul>
                        <p>
                            <a class="btn btn-dark" v-bind:href="`/article/${article.id}`">Read more</a>
                        </p><br>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <nav>
                <ul class="pagination pagination-lg justify-content-center">
                    <li class="page-item" v-if="page!==1">
                        <a class="page-link" href="javascript:void(0)" @click="page > 1 ? page-- : 1">Previous</a>
                    </li>
                    <li class="page-item" v-if="page < (articles.paginatorInfo ? articles.paginatorInfo.lastPage:1)">
                        <a class="page-link" href="javascript:void(0)"
                           @click="page<articles.paginatorInfo ? articles.paginatorInfo.lastPage: 2 ? page++ : articles.paginatorInfo ? articles.paginatorInfo.lastPage: 1">Next</a>
                    </li>
                </ul>
            </nav>
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

.page-timeline {
    margin: 30px auto 0 auto;
    position: relative;
    max-width: 1000px;
}

.page-timeline-line:before {
    position: absolute;
    content: '';
    top: 0;
    bottom: 0;
    left: 303px;
    right: auto;
    height: 100%;
    width: 3px;
    background: #3498db;
    z-index: 0;

    -webkit-box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.2), 0 6px 10px 0 rgba(0, 0, 0, 0.3);
    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.2), 0 6px 10px 0 rgba(0, 0, 0, 0.3);
}

.page-timeline-after:after {
    position: absolute;
    content: '';
    width: 3px;
    height: 40px;
    background: #3498db;
    background: -webkit-linear-gradient(top, #4782d3, rgba(52, 152, 219, 0));
    background: linear-gradient(to bottom, #4782d3, rgba(52, 152, 219, 0));
    top: 100%;
    left: 303px;

    -webkit-box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.2), 0 6px 10px 0 rgba(0, 0, 0, 0.3);
    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.2), 0 6px 10px 0 rgba(0, 0, 0, 0.3);
}

.vtimeline-content {
    margin-left: 350px;
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

.vtimeline-point {
    position: relative;
    display: block;
    vertical-align: top;
    margin-bottom: 30px;
}

.vtimeline-icon {
    position: relative;
    color: #fff;
    width: 50px;
    height: 50px;
    background: #4782d3;
    border-radius: 50%;
    float: left;
    text-align: center;
    line-height: 50px;
    z-index: 99;
    margin-left: 280px;
}

.vtimeline-icon i {
    display: block;
    font-size: 1.5em;
    line-height: 50px;

}

.vtimeline-date {
    width: 260px;
    text-align: right;
    position: absolute;
    left: 0;
    top: 10px;
    font-weight: 400;
    color: #374054;
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
}

.mb20 {
    margin-bottom: 20px !important;
}
</style>

<script>
import gql from "graphql-tag";

const articleFields = gql`fragment ArticleFields on Article {
    id
    title
    image
    created_at
    author {
        name
    }
}`;

export default {
    name: 'article-list',
    mounted() {
    },
    data() {
        return {
            articles: [],
            page: 1,
        }
    },
    apollo: {
        articles: {
            query: gql`query SearchArticle($page: Int!) {
                  articles( orderBy: [{ order: ASC, column: CREATED_AT}], first: 2, page: $page) {
                    paginatorInfo {
                      count
                      currentPage
                      total
                      perPage
                      lastPage
                    }
                    data {
                     ...ArticleFields
                    }
                  }
                }
                ${articleFields}
            `,
            variables() {
                return {
                    page: this.page
                }
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
                    let existArticle = this.articles.data.find(article => article.id === data.articleUpdated.id);
                    if (this.articles.data.indexOf(existArticle) > -1) {
                        this.$set(this.articles.data, this.articles.data.indexOf(existArticle), data.articleUpdated);
                    }
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
                    console.log(data.articleDeleted);
                    this.articles.data = this.articles.data.filter(entry => entry.id === data.articleDeleted.id)
                },
            },
        },
    },
}
</script>
