import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router";
import LoginView from "../auth/Login.vue";
import RegisterView from "../auth/Register.vue";
import TodoView from "../auth/Todo.vue";

const routes: Array<RouteRecordRaw> = [
  {
    path: "/login",
    name: "login",
    component: LoginView,
  },
  {
    path: "/register",
    name: "register",
    component: RegisterView,
  },
  {
    path: "/",
    name: "todo",
    component: TodoView,
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: "/:pathMatch(.*)*",
    name: "error",
    component: () => import("../auth/404page.vue"),
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

router.beforeEach((to, from, next) => {
  const token = sessionStorage.getItem("token");
  const requiresAuth = to.matched.some((record) => record.meta.requiresAuth);

  if (requiresAuth && !token) {
    next("/login");
  } else if (token && (to.name === "login" || to.name === "register")) {
    next("/");
  } else {
    next();
  }
});

export default router;
