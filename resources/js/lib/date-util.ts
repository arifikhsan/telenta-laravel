import dayjs from "dayjs";

export const formatStandardDate = (date: string): string => {
    if (!date) {
        return 'Empty Date'
    }
    return dayjs(date).format('YYYY-MM-DD HH:mm:ss'); // Format as needed
};
