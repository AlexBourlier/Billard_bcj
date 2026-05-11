import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";

import {
    faNewspaper,
} from "@awesome.me/kit-c0df283285/icons/classic/solid";

type Props = {
    children: React.ReactNode;
    id?: string;
}

export function SeeMore({ children, id }: Props) {
    return (
        <p className="seeMore" id={id}>
            <FontAwesomeIcon icon={faNewspaper} className="seeMoreIcon" /> {children}
        </p>
    );
}