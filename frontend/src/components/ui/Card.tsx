type Props = {
    children: React.ReactNode;
    className?: string;
}

export function ArticleCard({ children, className = "" }: Props) {
    return (
        <div className={`article-card ${className}`}>
            {children}
        </div>
    );
}