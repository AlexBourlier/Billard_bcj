import { useEffect, useState } from "react";

type AlertToastProps = {
    message: string;
    type?: "error" | "success" | "info";
    duration?: number;
    onClose: () => void;
};

export default function AlertToast({
    message,
    type = "error",
    duration = 4000,
    onClose,
}: AlertToastProps) {
    const [isLeaving, setIsLeaving] = useState(false);

    useEffect(() => {
        const timer = window.setTimeout(() => {
            setIsLeaving(true);
        }, duration);

        return () => window.clearTimeout(timer);
    }, [duration]);

    function handleAnimationEnd() {
        if (isLeaving) {
            onClose();
        }
    }

    return (
        <div
            className={`alert-toast alert-toast--${type} ${isLeaving ? "alert-toast--leaving" : ""
                }`}
            role={type === "error" ? "alert" : "status"}
            aria-live={type === "error" ? "assertive" : "polite"}
            onAnimationEnd={handleAnimationEnd}
        >
            {message}
        </div>
    );
}