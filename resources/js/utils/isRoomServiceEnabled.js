import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

export const isRoomServiceEnabled = computed(() => {
    const page = usePage();
    const cfg = page.props.roomServiceConfig ?? [];
    return cfg.length > 0 && cfg[0].is_active === 1;
});