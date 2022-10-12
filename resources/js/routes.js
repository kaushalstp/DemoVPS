
import store from './store';

import Login from './components/Login.vue';
import Register from './components/Register.vue';
import ForgotPassword from './components/ForgotPassword.vue';
import ResetPassword from './components/ResetPassword.vue';
import Dashboard from './components/Dashboard.vue';
import ValidateEmail from './components/ValidateEmail.vue';
import ValidateList from './components/ValidateList.vue';
import Invoice from './components/Invoice.vue'; 
import CreditHistory from './components/CreditHistory.vue'; 
import FAQ from './components/FAQ.vue';
import CreditPlans from './components/CreditPlans.vue';
import Terminology from './components/terminology.vue';

import UpdateProfile from './components/UpdateProfile.vue';
import ChangePassword from './components/ChangePassword.vue';
import DeleteAccount from './components/DeleteAccount.vue';

import Integration from './components/integration.vue';
import apilogs from './components/apilogs.vue';
import apisettings from './components/apisettings.vue';
import Affiliate from './components/affiliate.vue';
import Support from './components/Support.vue';
import FreeCredits from './components/FreeCredits.vue';
import Settings from './components/Settings.vue';
import SpecialDiscount from './components/SpecialDiscount.vue';
import SpecialDiscountRequest from './components/SpecialDiscountRequest.vue';


import AdminSettings from './components/AdminSettings.vue';
import Perks from './components/Perks.vue';


import Users from './components/Users.vue'; 
import Uploads from './components/Uploads.vue';
import Orders from './components/Orders.vue';
import Coupons from './components/Coupons.vue';
import Rewards from './components/Rewards.vue';

import SendAlert from './components/SendAlert.vue';
import Paynow from './components/paynow.vue';
import Reports from './components/Reports.vue';
import ReportsHighestOrders from './components/ReportsHighestOrders.vue'; 
import ReportsSubscribers from './components/ReportsSubscribers.vue'; 
import RevenueBySource from './components/RevenueBySource.vue'; 


//import Leftsidebar from './components/leftsidebar.vue';

 
export const routes = [    
    {
        name: 'Login', 
        path: '*',
        meta: {layout:"visitorboard"},
        component: Login        
    },
    /*{
        name: 'Coupons',
        path: '/Coupons',        
        component: Coupons,
       
    },        */
    {
        name: 'SpecialDiscount',
        path: '/SpecialDiscount',        
        component: SpecialDiscount,
       
    },            
    /*{
        name: 'SpecialDiscountRequest',
        path: '/SpecialDiscountRequest',        
        component: SpecialDiscountRequest,
       
    },            */
    {
        name: 'Perks',
        path: '/Perks',        
        component: Perks,
       
    },        
    /*{
        name: 'Rewards',
        path: '/Rewards',        
        component: Rewards,
       
    },        */
    {
        name: 'Register',
        path: '/Register',
        meta: {layout:"visitorboard"},
        component: Register
    },
    {
        name: 'Paynow',
        path: '/Paynow',
        meta: {layout:"visitorboard"},
        component: Paynow,
        
    },
    {
        name: 'ForgotPassword',
        path: '/ForgotPassword',
        meta: {layout:"visitorboard"},
        component: ForgotPassword
    },        
    {
        name: 'ResetPassword/',
        path: '/ResetPassword/:token',
        meta: {layout:"visitorboard"},
        component: ResetPassword
    },        
    {
        name: 'dashboard',
        path: '/dashboard', 
        component: Dashboard,                
    },    
    /*{
        name: 'users',
        path: '/users', 
        component: Users,     
        
    },    
    {
        name: 'uploads',
        path: '/uploads', 
        component: Uploads,        
        
    },    
    {
        name: 'orders',
        path: '/orders', 
        component: Orders,        
        
    }, 
    {
        name: 'ReportsHighestOrders',
        path: '/ReportsHighestOrders', 
        component: ReportsHighestOrders,        
        
    }, 
    {
        name: 'ReportsSubscribers',
        path: '/ReportsSubscribers', 
        component: ReportsSubscribers,        
        
    }, 
    {
        name: 'RevenueBySource',
        path: '/RevenueBySource', 
        component: RevenueBySource,        
        
    },     
    {
        name: 'Reports',
        path: '/Reports', 
        component: Reports,        
        
    }, 
    {
        name: 'sendalert',
        path: '/sendalert', 
        component: SendAlert,    
        
    },    */
    {
        name: 'ValidateEmail',
        path: '/ValidateEmail',
        component: ValidateEmail,
        
    },    
    {
        name: 'ValidateList',
        path: '/ValidateList',
        component: ValidateList,
        
    },    
    {
        name: 'Invoice',
        path: '/Invoice',
        component: Invoice,
        
    },    
    {
        name: 'CreditHistory',
        path: '/CreditHistory',
        component: CreditHistory,
        
    },    
    {
        name: 'FAQ',
        path: '/FAQ',
        component: FAQ,
        
    },    
    {
        name: 'CreditPlans',
        path: '/CreditPlans',
        component: CreditPlans,
       
    },    
    {
        name: 'Terminology',
        path: '/Terminology',
        component: Terminology,  
       
    },
    {
        name: 'Integration',  
        path: '/Integration',
        component: Integration, 
        
    },    
    {
        name: 'Login',
        path: 'Login',
        meta: {layout:"visitorboard"},
        component: Login
    }, 
    {
        name: 'UpdateProfile',
        path: '/UpdateProfile',
        component: UpdateProfile,
       
    }, 
    {
        name: 'ChangePassword',
        path: '/ChangePassword',
        component: ChangePassword,
        
    }, 
    {
        name: 'DeleteAccount',
        path: '/DeleteAccount',
        component: DeleteAccount,
        
    }, 
    {
        name: 'apilogs',
        path: '/apilogs',
        component: apilogs,
        
    }, 
    {
        name: 'apisettings',
        path: '/apisettings',
        component: apisettings,
        
    }, 
    {
        name: 'Settings',
        path: '/Settings',
        component: Settings,
        
    }, 
    {
        name: 'AdminSettings',
        path: '/AdminSettings',
        component: AdminSettings,
        
    }, 
    {
        name: 'FreeCredits',
        path: '/FreeCredits',
        component: FreeCredits,
        
    }, 
    {
        name: 'Affiliate',
        path: '/Affiliate',
        component: Affiliate,
        
    }, 
    {
        name: 'Support',
        path: '/Support',
        component: Support,
        
    },     
];


 