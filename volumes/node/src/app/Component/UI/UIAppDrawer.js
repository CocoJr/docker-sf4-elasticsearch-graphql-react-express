/*
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

import React from 'react';
import {Link} from "react-router-dom";
import {URL_ADMIN_USERS, URL_DASHBOARD} from "../../routes";
import {isAdmin, isEmptyObject} from "../../Util/core";
import DashboardIcon from "@material-ui/icons/es/Dashboard";
import DescriptionIcon from "@material-ui/icons/es/Description";
import InboxIcon from "@material-ui/icons/es/Inbox";
import Drawer from "@material-ui/core/es/Drawer/Drawer";
import List from "@material-ui/core/es/List/List";
import ListItem from "@material-ui/core/es/ListItem/ListItem";
import ListItemIcon from "@material-ui/core/es/ListItemIcon/ListItemIcon";
import ListItemText from "@material-ui/core/es/ListItemText/ListItemText";
import Divider from "@material-ui/core/es/Divider/Divider";
import ExpandLess from "@material-ui/icons/es/ArrowDropDown";
import ExpandMore from "@material-ui/icons/es/KeyboardArrowRight";
import Collapse from "@material-ui/core/es/Collapse/Collapse";

class UIAppDrawer extends React.Component
{
    render() {
        const {classes, t, i18n, user, open} = this.props;

        if (isEmptyObject(user)) {
            return (
                <Drawer
                    variant="persistent"
                    anchor={'left'}
                    open={false}
                    docked="true"
                    classes={{
                        paper: classes.drawerPaper,
                    }}
                />
            );
        }

        return (
            <Drawer
                variant="persistent"
                anchor={'left'}
                open={open}
                docked="true"
                classes={{
                    paper: classes.drawerPaper,
                }}
            >
                <div className={classes.list}>
                    <List>
                        <li>
                            <ListItem button component={Link} to={'/' + i18n.language + URL_DASHBOARD}>
                                <ListItemIcon>
                                    <DashboardIcon/>
                                </ListItemIcon>
                                <ListItemText primary={t('menu.dashboard')}/>
                            </ListItem>
                        </li>
                    </List>
                </div>
                {this.renderAdminDrawerList(user)}
            </Drawer>
        );
    }

    renderAdminDrawerList(user) {
        if (!isAdmin(user)) {
            return;
        }

        const {t, i18n, classes, adminOpen} = this.props;

        return ([
            <Divider key={0}/>,
            <List key={1}>
                <li>
                    <ListItem button onClick={this.props.handleAdminCollapse}>
                        <ListItemIcon>
                            <DescriptionIcon/>
                        </ListItemIcon>
                        <ListItemText inset primary={"Admin"} />
                        {adminOpen ? <ExpandLess /> : <ExpandMore />}
                    </ListItem>
                </li>
                <Collapse key={1} in={adminOpen} timeout="auto" unmountOnExit>
                    <List className={classes.sublistItem}>
                        <li>
                            <ListItem button component={Link} to={'/' + i18n.language + URL_ADMIN_USERS}>
                                <ListItemIcon>
                                    <InboxIcon />
                                </ListItemIcon>
                                <ListItemText primary={t('menu.admin.users')} />
                            </ListItem>
                        </li>
                    </List>
                </Collapse>
            </List>
        ]);
    }
}

export default UIAppDrawer;