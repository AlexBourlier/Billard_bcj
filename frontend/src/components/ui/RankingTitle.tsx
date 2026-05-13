type Props = {
    children: React.ReactNode;
    id?: string;
    className?: string;
};

export function RankingTitle({ children, id, className = "" }: Props) {
    return (
        <h3 className={`ranking-title ${className}`} id={id}>
            {children}
        </h3>
    );
}