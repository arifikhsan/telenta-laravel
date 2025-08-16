import dayjs from "dayjs";

export const formatStandardDate = (date: string): string => {
    return dayjs(date).format('YYYY-MM-DD HH:mm:ss'); // Format as needed
};
