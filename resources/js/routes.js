import Dashboard from './components/DashboardComponent.vue';
import Setting from './components/Settings.vue';
import Users from './components/users.vue';

export default[
    {
        path:'/admin/dashboard',
        name:'admin.dashboard',
        component: Dashboard,
    },

    {
        path:'/admin/settings',
        name:'admin.settings',
        component: Setting,
    },
    {
        path:'/admin/users',
        name:'admin.users',
        component: Users,
    }
]