import { usePage } from "@inertiajs/vue3";

const props = usePage().props;
console.log('Room Service Config in utils:', props.RoomServiceConfig);

export const calculateServiceCost = (total) => {
    const isRoomServiceActive =
    props.RoomServiceConfig && props.RoomServiceConfig.is_active === 1;
    const serviceCostPercent =
    isRoomServiceActive && props.RoomServiceConfig.service_cost
        ? props.RoomServiceConfig.service_cost
        : 0;
    if (isRoomServiceActive) {
       total = parseFloat(total);
       const serviceCost = (total * serviceCostPercent) / 100;
        return total + serviceCost;
    }
    return total;
};