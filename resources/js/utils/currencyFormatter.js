export const formatter = new Intl.NumberFormat('es-MX', {
    style: 'currency',
    currency: 'MXN',
    minimumFractionDigits: 2
});

export const formatterWithoutFraction = new Intl.NumberFormat('es-MX', {
    minimumFractionDigits: 0
});