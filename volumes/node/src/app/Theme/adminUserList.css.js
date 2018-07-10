/*
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

import {fromEvent} from 'rxjs';

const styles = theme => ({
    tableWrapper: {
        overflowX: "auto",
        width: 'auto',
    },
    item: {
        margin: "10px !important",
    },
    imgProfil: {
        width: "30px",
        height: "30px",
        borderRadius: "100%",
        objectFit: "cover",
        objectPosition: "center",
    },
    noWrap: {
        whiteSpace: "nowrap",
    },
    pagination: {
        flex: '0 0 auto',
    },
    spacer: {
        flex: '1 1 100%',
    },
});

export default styles;