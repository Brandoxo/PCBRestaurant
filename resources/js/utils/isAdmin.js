import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

export function isAdmin() {
    const user = usePage().props.auth.user; 
    return user && user.roles && user.roles.includes("Admin");
};
