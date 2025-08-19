export interface QuestionPositionMapEntity {
    id: number;
    position: {
        id: number;
        name: string;
    }
    question: {
        id: number;
        question: string;
    }
    created_at: string;
    updated_at: string;
}
