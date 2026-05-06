type Props = {
    children: React.ReactNode;
    id?: string;
}

export function ArticleTitle({ children, id }: Props) {
    return (
        <h2 className="article-title" id={id}>
            {children}
        </h2>
    );
}