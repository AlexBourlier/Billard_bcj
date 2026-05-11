type Props = {
    children: React.ReactNode;
    id?: string;
}

export function Signets({ children, id }: Props) {
    return (
        <p className="signets" id={id}>
            {children}
        </p>
    );
}