export interface CandidateEntity {
    id: number;
    name: string;
    position_id: number;
    position: {
        id: number;
        name: string;
    }
    manager_id: number;
    manager: {
        id: number;
        name: string;
    }
    cv_url: string;
    cv_path: string;
    status: string;
    days_required: number;
    proposed_date: string;
    cv_review_date: string;
    hr_interview_date: string;
    internal_interview_date: string;
    user_interview_date: string;
    created_at: string;
    updated_at: string;
}
