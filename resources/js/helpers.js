export function checkOutsideElemClick(e, targetId) {
    let currentElem = e.target
    let clickedInTarget = false;

    const checkIsTarget = () => {
        if (currentElem.hasAttribute('id')) {
            if (currentElem.id == targetId) {
                clickedInTarget = true;
            }
        }
    }

    checkIsTarget()

    while (currentElem.tagName != 'BODY' && !clickedInTarget) {
        currentElem = currentElem.parentElement;
        checkIsTarget()
    }

    return clickedInTarget;
}