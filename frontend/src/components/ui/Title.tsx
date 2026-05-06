type Props = {
    children: React.ReactNode;
}

export function ArticleTitle({ children }: Props) {
    return (
        <h2 className="article-title">
            {children}
        </h2>
    );
}