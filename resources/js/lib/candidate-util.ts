const statusOptions = [
    { value: 'cv_reviewed', label: 'CV Reviewed' },
    { value: 'hr_interviewed', label: 'HR Interviewed' },
    { value: 'hired', label: 'Hired' },
];

export function getStatusLabel(value: string): string {
    const option = statusOptions.find(option => option.value === value);
    return option ? option.label : value;
}
