
export const statusOptions = [
    { value: 'cv_reviewed', label: 'CV Reviewed' },
    { value: 'hr_interviewed', label: 'HR Interviewed' },
    { value: 'internal_interviewed', label: 'Internal Interviewed' },
    { value: 'user_interviewed', label: 'User Interviewed' },
    { value: 'hired', label: 'Hired' },
    { value: 'rejected_by_manager', label: 'Rejected by Manager' },
];

export function getStatusLabel(value: string): string {
    const option = statusOptions.find(option => option.value === value);
    return option ? option.label : value;
}
