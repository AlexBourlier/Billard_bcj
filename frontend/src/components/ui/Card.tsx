type Props = {
    children: React.ReactNode;
    className?: string;
}

export function ArticleCard({ children, className = "" }: Props) {
    return (
        <article className={`article-card ${className}`}>
            {children}
        </article>
    );
}