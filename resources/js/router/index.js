import { createRouter, createWebHistory } from 'vue-router';
import Main from '../views/Main.vue';
import Registration from '../views/Registration.vue';
import Authorization from "../views/Authorization.vue";
import Profile from "../views/Profile.vue";
import Collections from "../views/Collections.vue";
import Recommendations from "../views/Recommendations.vue";
import AdminPanel from "../views/AdminPanel.vue";
import SuggestedMovies from "../views/SuggestedMovies.vue";
import Suggestion from "../views/Suggestion.vue";
import Suggested from "../views/Suggested.vue";
import Collection from "../views/Collection.vue";
import Film from  "../views/Film.vue";
import Page404 from '../views/Page404.vue';


const routes = [
    {
        path: '/',
        name: 'Main',
        component: Main,
    },
    {
        path: '/registration',
        name: 'Registration',
        component: Registration,
    },
    {
        path: '/authorization',
        name: 'Authorization',
        component: Authorization
    },
    {
        path: '/profile/:userID?',
        name: 'Profile',
        component: Profile
    },
    {
        path: '/profile/suggested:userID?',
        name: 'Suggested',
        component: Suggested
    },
    {
        path: '/admin/:userID?',
        name: 'AdminPanel',
        component: AdminPanel,
    },
    {
        path: '/admin/suggestedmovies/:userID?',
        name: 'SuggestedMovies',
        component: SuggestedMovies,
    },
    {
        path: '/admin/suggestedmovies/suggestion/:userID?/:suggestedUserID?/:id?',
        name: 'Suggestion',
        component: Suggestion,
        props: true,
    },
    {
        path: '/collections/:userID?',
        name: 'Collections',
        component: Collections
    },
    {
        path: '/collections/collection/:userID?/:id?',
        name: 'Collection',
        component: Collection
    },
    {
        path: '/recommendations/:userID?',
        name: 'Recommendations',
        component: Recommendations
    },
    {
        path: '/film/:userID?/:id?',
        name: 'Film',
        component: Film
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'NotFound',
        component: Page404
    },
]

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
})

export default router
