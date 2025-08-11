export interface Candidate {
    id: number;
    name: string;
    position: {
        id: number;
        name: string;
    }
    manager: {
        id: number;
        name: string;
    }
    cv_url: string;
    status: string;
    days_required: number;
    proposed_date: string;
    cv_review_date: string;
    hr_interview_date: string;
    created_at: string;
    updated_at: string;
}
