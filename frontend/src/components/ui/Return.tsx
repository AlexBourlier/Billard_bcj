import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";

import {
    faArrowUTurnDownLeft,
} from "@awesome.me/kit-c0df283285/icons/classic/solid";

type Props = {
    children: React.ReactNode;
    id?: string;
    className?: string;
};

export function Return({ children, id, className = "" }: Props) {
    return (
        <div className={`return ${className}`} id={id}>
            <FontAwesomeIcon
                icon={faArrowUTurnDownLeft}
                className="returnIcon"
            />
            {children}
        </div>
    );
}