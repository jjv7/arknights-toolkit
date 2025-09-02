import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Community from '../views/Community.vue'
import NewsSearch from '../components/news/NewsSearch.vue'
import NewsArticle from '../components/news/NewsArticle.vue'
import PostSearch from '../components/posts/PostSearch.vue'
import PostPage from '../components/posts/PostPage.vue'
import CreatePost from '../components/posts/CreatePost.vue'
import EditPost from '../components/posts/EditPost.vue'
import About from '../views/About.vue'
import Drops from '../views/Drops.vue'
import Login from '../views/Login.vue'
import SignUp from '../views/SignUp.vue'

const routes = [
    { path: '/', component: Home },
    {
        path: '/community',
        component: Community,
        children: [
            { path: '', redirect: '/community/news' },
            { path: 'news', component: NewsSearch },
            { path: 'news/:id', component: NewsArticle, props: true },
            { path: 'social', component: PostSearch },
            { path: 'social/create', component: CreatePost },
            { path: 'social/posts/:id', component: PostPage, props: true },
            { path: 'social/posts/edit/:id', component: EditPost, props: true }
        ]
    },
    { path: '/drops', component: Drops },
    { path: '/about', component: About },
    { path: '/login', component: Login },
    { path: '/signup', component: SignUp }
];
const router = createRouter({
    history: createWebHistory('/ak-toolkit/'),
    routes
});

export default router