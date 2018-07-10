/*
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

import {fromEvent} from 'rxjs';
import red from "@material-ui/core/colors/red";

const drawerWidth = 320;

const styles = theme => ({
    body: {
        margin: 0,
    },
    root: {
        flexGrow: 1,
    },
    flex: {
        flex: 1,
    },
    appFrame: {
        zIndex: 1,
        overflow: 'hidden',
        position: 'relative',
        display: 'flex',
        width: '100%',
    },
    appBar: {
        transition: theme.transitions.create(['margin', 'width'], {
            easing: theme.transitions.easing.sharp,
            duration: theme.transitions.duration.leavingScreen,
        }),
    },
    appBarShift: {
        width: `calc(100% - ${drawerWidth}px)`,
        transition: theme.transitions.create(['margin', 'width'], {
            easing: theme.transitions.easing.easeOut,
            duration: theme.transitions.duration.enteringScreen,
        }),
        marginLeft: drawerWidth,
    },
    menuButton: {
        marginLeft: -12,
        marginRight: 5,
    },
    hide: {
        display: 'none',
    },
    drawerPaper: {
        position: 'fixed',
        width: drawerWidth,
        height: window
            ? fromEvent(window, 'resize')
                .subscribe(() => {
                    return window.innerHeight;
                })
                .next(() => {
                    return window.innerHeight;
                })
            : "100vh",

    },
    drawerHeader: {
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'flex-end',
        padding: '0 8px',
    },
    content: {
        width: "100%",
        flexGrow: 1,
        backgroundColor: theme.palette.background.default,
        padding: theme.spacing.unit * 2,
        transition: theme.transitions.create('margin', {
            easing: theme.transitions.easing.sharp,
            duration: theme.transitions.duration.leavingScreen,
        }),
        marginTop: theme.mixins.toolbar.minHeight,
    },
    contentShift: {
        transition: theme.transitions.create('margin', {
            easing: theme.transitions.easing.easeOut,
            duration: theme.transitions.duration.enteringScreen,
        }),
        marginLeft: drawerWidth,
    },
    item: {
        margin: "10px !important",
    },
    sublistItem: {
        backgroundColor: theme.palette.background.default,
    },
    plainPage: {
        position: "absolute",
        top: 0,
        left: 0,
        width: "100%",
        height: "100vh",
        backgroundColor: "white",
        zIndex: 1000,
    },
    imgProfil: {
        width: "30px",
        height: "30px",
        borderRadius: "100%",
        objectFit: "cover",
        objectPosition: "center",
    },
    error: {
        color: red[800],
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