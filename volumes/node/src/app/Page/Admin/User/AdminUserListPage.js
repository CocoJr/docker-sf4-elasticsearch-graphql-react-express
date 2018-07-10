/*
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */
import React from "react";
import withStyles from '@material-ui/core/es/styles/withStyles';
import styles from "../../../Theme/adminUserList.css";
import {withApollo} from "react-apollo/index";
import Query from "react-apollo/Query";
import adminUserGql from "../../../Gql/Admin/User/list";
import Spinner from "../../../Component/Core/spinner";

import Grid from "@material-ui/core/es/Grid/Grid";
import Typography from "@material-ui/core/es/Typography/Typography";
import Table from '@material-ui/core/es/Table/Table';
import TableBody from '@material-ui/core/es/TableBody/TableBody';
import TableCell from '@material-ui/core/es/TableCell/TableCell';
import TableHead from '@material-ui/core/es/TableHead/TableHead';
import TableFooter from '@material-ui/core/es/TableFooter/TableFooter';
import TableRow from '@material-ui/core/es/TableRow/TableRow';
import Paper from '@material-ui/core/es/Paper/Paper';
import IconButton from '@material-ui/core/es/IconButton/IconButton';
import FirstPageIcon from '@material-ui/icons/es/FirstPage';
import KeyboardArrowLeft from '@material-ui/icons/es/KeyboardArrowLeft';
import KeyboardArrowRight from '@material-ui/icons/es/KeyboardArrowRight';
import LastPageIcon from '@material-ui/icons/es/LastPage';
import MenuItem from "@material-ui/core/es/MenuItem/MenuItem";
import TextField from "@material-ui/core/es/TextField/TextField";
import ModeEditIcon from '@material-ui/icons/es/ModeEdit';
import DoneIcon from '@material-ui/icons/es/Done';
import SearchIcon from '@material-ui/icons/es/Search';

import {translate} from "react-i18next";
import Switch from "@material-ui/core/es/Switch/Switch";
import Mutation from "react-apollo/Mutation";
import adminUserEditProfil from "../../../Gql/Admin/User/editProfil.js";
import Moment from "react-moment";
import InputField from "../../../Component/Core/Form/InputField";
import {findDOMNode} from "react-dom";
import adminUserUploadImgProfilMutation from "../../../Gql/Admin/User/uploadImgProfil";
import TableSortLabel from "@material-ui/core/es/TableSortLabel/TableSortLabel";
import Toolbar from "@material-ui/core/es/Toolbar/Toolbar";

class AdminUserListPage extends React.Component {
    constructor(props) {
        super(props);

        this.handleFirstPageButtonClick = this.handleFirstPageButtonClick.bind(this);
        this.handleBackButtonClick = this.handleBackButtonClick.bind(this);
        this.handleNextButtonClick = this.handleNextButtonClick.bind(this);
        this.handleLastPageButtonClick = this.handleLastPageButtonClick.bind(this);
        this.handleChangeLimit = this.handleChangeLimit.bind(this);

        this.state = {
            page: 1,
            limit: 10,
            orderBy: 'id',
            orderDir: 'desc',
            searches: null,
            editUsername: false,
            editEmail: false,
            editRegistratedAt: false,
        }
    }

    render() {
        const {classes, t} = this.props;
        const {page, limit, orderBy, orderDir, searches} = this.state;

        let editEmailField, editUsernameField, editRegistratedAtField = null;

        return (
            <Grid container justify={"center"} spacing={16}>
                <Grid item xs={12}>
                    <Typography variant="headline" gutterBottom align="center">
                        {t('title')}
                    </Typography>
                </Grid>
                <Grid item xs={12}>
                    <Query query={adminUserGql} variables={{page, limit, orderBy, orderDir, searches}}>
                        {({data: {adminUsers}, loading}) => {
                            let ccount = adminUsers ? adminUsers.total : 0;
                            let cpage = adminUsers ? adminUsers.page : page;
                            let climit = adminUsers ? adminUsers.limit : limit;
                            let spinnerCellHeight = 48 * 5;

                            return (
                                <Paper className={classes.root}>
                                    <div className={classes.tableWrapper}>
                                        <Table className={classes.table}>
                                            <TableHead>
                                                {this.renderPagination(cpage, ccount, climit)}
                                                {this.renderHeader()}
                                            </TableHead>
                                            <TableBody>
                                                {loading &&
                                                <TableRow>
                                                    <TableCell colSpan={5} style={{height: spinnerCellHeight, textAlign: 'center'}}>
                                                        <Spinner />
                                                    </TableCell>
                                                </TableRow>
                                                }

                                                {!loading && adminUsers.items.length > 0 && adminUsers.items.map((item) => {
                                                    return (
                                                        <Mutation mutation={adminUserEditProfil} key={item.id}>
                                                            {(adminUserEditProfil, {loading, error, data}) => {
                                                                let formErrors = data && data.adminUserEditProfil && data.adminUserEditProfil.errors.length && data.adminUserEditProfil.user.id === item.id
                                                                    ? data.adminUserEditProfil.errors
                                                                    : null;

                                                                let isActive = data && data.adminUserEditProfil && data.adminUserEditProfil.user
                                                                    ? data.adminUserEditProfil.user.isActive
                                                                    : item.isActive;

                                                                if (formErrors && this.state.editUsername && this.state.editUsername !== item.id) {
                                                                    this.setState({editUsername: item.id});
                                                                } else if (formErrors && this.state.editEmail && this.state.editEmail !== item.id) {
                                                                    this.setState({editEmail: item.id});
                                                                } else if (formErrors && this.state.editRegistratedAt && this.state.editRegistratedAt !== item.id) {
                                                                    this.setState({editRegistratedAt: item.id});
                                                                }

                                                                return (
                                                                    <TableRow>
                                                                        <TableCell className={classes.noWrap}>{item.id}</TableCell>
                                                                        <TableCell className={classes.noWrap}>
                                                                            {(this.state.editUsername === item.id || InputField.getErrors(formErrors, 'username').length > 0) &&
                                                                                <InputField
                                                                                    name={"username"}
                                                                                    id={"username"}
                                                                                    type={"text"}
                                                                                    label={t('username')}
                                                                                    fullWidth={false}
                                                                                    errors={formErrors}
                                                                                    endAdornment={
                                                                                        <DoneIcon style={{cursor: 'pointer'}}
                                                                                                  size={32}
                                                                                                  onClick={() => {
                                                                                                      let newUser = Object.assign({}, item);
                                                                                                      newUser.username = editUsernameField.value;
                                                                                                      adminUserEditProfil({
                                                                                                          variables: {
                                                                                                              id: item.id,
                                                                                                              username: editUsernameField.value
                                                                                                          },
                                                                                                          refetchQueries: [{ query: adminUserGql, variables: {page, limit, orderBy, orderDir} }],
                                                                                                          optimisticResponse: {
                                                                                                              __typename: "Mutation",
                                                                                                              adminUserEditProfil: {
                                                                                                                  __typename: 'User',
                                                                                                                  user: newUser,
                                                                                                                  errors: {
                                                                                                                      key: null,
                                                                                                                      message: null,
                                                                                                                      __typename: 'FormError'
                                                                                                                  }
                                                                                                              }
                                                                                                          },
                                                                                                          update: (proxy, {data: {adminUserEditProfil}}) => {
                                                                                                              this.updateCache(proxy, adminUserEditProfil);
                                                                                                          },
                                                                                                      });
                                                                                                      this.editField('editUsername', false);
                                                                                                  }}
                                                                                        />
                                                                                    }
                                                                                    defaultValue={item.username}
                                                                                    ref={node => {
                                                                                        if (node) {
                                                                                            node = findDOMNode(node).getElementsByTagName('input')[0];
                                                                                        }

                                                                                        editUsernameField = node;
                                                                                    }}
                                                                                /> ||
                                                                                <div onClick={this.editField.bind(this, 'editUsername', item.id)}>
                                                                                    {item.username + ''}<ModeEditIcon />
                                                                                </div>
                                                                            }
                                                                        </TableCell>
                                                                        <TableCell className={classes.noWrap}>
                                                                            {(this.state.editEmail === item.id || InputField.getErrors(formErrors, 'email').length > 0) &&
                                                                            <InputField
                                                                                name={"email"}
                                                                                id={"email"}
                                                                                type={"text"}
                                                                                label={t('email')}
                                                                                fullWidth={false}
                                                                                errors={formErrors}
                                                                                endAdornment={
                                                                                    <DoneIcon style={{cursor: 'pointer'}}
                                                                                              size={32}
                                                                                              onClick={() => {
                                                                                                  let newUser = Object.assign({}, item);
                                                                                                  newUser.email = editEmailField.value;
                                                                                                  adminUserEditProfil({
                                                                                                      variables: {
                                                                                                          id: item.id,
                                                                                                          email: editEmailField.value
                                                                                                      },
                                                                                                      refetchQueries: [{ query: adminUserGql, variables: {page, limit, orderBy, orderDir} }],
                                                                                                      optimisticResponse: {
                                                                                                          __typename: "Mutation",
                                                                                                          adminUserEditProfil: {
                                                                                                              __typename: 'User',
                                                                                                              user: newUser,
                                                                                                              errors: {
                                                                                                                  key: null,
                                                                                                                  message: null,
                                                                                                                  __typename: 'FormError'
                                                                                                              }
                                                                                                          }
                                                                                                      },
                                                                                                      update: (proxy, {data: {adminUserEditProfil}}) => {
                                                                                                          this.updateCache(proxy, adminUserEditProfil);
                                                                                                      },
                                                                                                  });
                                                                                                  this.editField('editEmail', false);
                                                                                              }}
                                                                                    />
                                                                                }
                                                                                defaultValue={item.email}
                                                                                ref={node => {
                                                                                    if (node) {
                                                                                        node = findDOMNode(node).getElementsByTagName('input')[0];
                                                                                    }

                                                                                    editEmailField = node;
                                                                                }}
                                                                            /> ||
                                                                            <div onClick={this.editField.bind(this, 'editEmail', item.id)}>
                                                                                {item.email + ''}<ModeEditIcon />
                                                                            </div>
                                                                            }
                                                                        </TableCell>
                                                                        <TableCell className={classes.noWrap}>
                                                                            {(this.state.editRegistratedAt === item.id || InputField.getErrors(formErrors, 'registratedAt').length > 0) &&
                                                                            <InputField
                                                                                name={"registratedAt"}
                                                                                id={"registratedAt"}
                                                                                type={"datetime-local"}
                                                                                label={t('registratedAt')}
                                                                                fullWidth={false}
                                                                                errors={formErrors}
                                                                                endAdornment={
                                                                                    <DoneIcon style={{cursor: 'pointer'}}
                                                                                              size={32}
                                                                                              onClick={() => {
                                                                                                  let newUser = Object.assign({}, item);
                                                                                                  newUser.registratedAt = editRegistratedAtField.value;
                                                                                                  adminUserEditProfil({
                                                                                                      variables: {
                                                                                                          id: item.id,
                                                                                                          registratedAt: editRegistratedAtField.value
                                                                                                      },
                                                                                                      refetchQueries: [{ query: adminUserGql, variables: {page, limit, orderBy, orderDir} }],
                                                                                                      optimisticResponse: {
                                                                                                          __typename: "Mutation",
                                                                                                          adminUserEditProfil: {
                                                                                                              __typename: 'User',
                                                                                                              user: newUser,
                                                                                                              errors: {
                                                                                                                  key: null,
                                                                                                                  message: null,
                                                                                                                  __typename: 'FormError'
                                                                                                              }
                                                                                                          }
                                                                                                      },
                                                                                                      update: (proxy, {data: {adminUserEditProfil}}) => {
                                                                                                          this.updateCache(proxy, adminUserEditProfil);
                                                                                                      },
                                                                                                  });
                                                                                                  this.editField('editRegistratedAt', false);
                                                                                              }}
                                                                                    />
                                                                                }
                                                                                defaultValue={item.registratedAt}
                                                                                ref={node => {
                                                                                    if (node) {
                                                                                        node = findDOMNode(node).getElementsByTagName('input')[0];
                                                                                    }

                                                                                    editRegistratedAtField = node;
                                                                                }}
                                                                            /> ||
                                                                            <div onClick={this.editField.bind(this, 'editRegistratedAt', item.id)}>
                                                                                <Moment>{item.registratedAt}</Moment>{' '}<ModeEditIcon />
                                                                            </div>
                                                                            }
                                                                        </TableCell>
                                                                        <TableCell>
                                                                            <Mutation mutation={adminUserUploadImgProfilMutation}>
                                                                                {(adminUserUploadImgProfil, { loading, error, data }) => {
                                                                                    let formErrors = data && data.adminUserUploadImgProfil.errors.length > 0 ? data.adminUserUploadImgProfil.errors : null;

                                                                                    return (
                                                                                        <div>
                                                                                            {item.imgProfil != null && item.imgProfil.publicPath && item.imgProfil.publicPath.length
                                                                                                && (item.imgProfil.publicPath === 'loading' ? <Spinner/> : <img style={{maxWidth: "100%"}} src={'http://site.local:8080/' + item.imgProfil.publicPath} />)
                                                                                            }
                                                                                            <InputField
                                                                                                name={"imgProfil_file_"+item.id}
                                                                                                id={"imgProfil_file_"+item.id}
                                                                                                type={"file"}
                                                                                                label={t('imgProfilUpload')}
                                                                                                fullWidth={false}
                                                                                                errors={formErrors}
                                                                                                onChange={({ target: { validity, files: [file] } }) => {
                                                                                                    if (validity.valid) {
                                                                                                        let newUser = Object.assign({}, item);
                                                                                                        newUser.imgProfil = {
                                                                                                            id: newUser.imgProfil ? newUser.imgProfil.id : null,
                                                                                                            publicPath: 'loading',
                                                                                                            __typename: 'File',
                                                                                                        };

                                                                                                        adminUserUploadImgProfil({
                                                                                                            variables: {
                                                                                                                id: item.id,
                                                                                                                file: file
                                                                                                            },
                                                                                                            optimisticResponse: {
                                                                                                                __typename: "Mutation",
                                                                                                                adminUserUploadImgProfil: {
                                                                                                                    __typename: 'File',
                                                                                                                    file: newUser.imgProfil,
                                                                                                                    errors: {
                                                                                                                        key: null,
                                                                                                                        message: null,
                                                                                                                        __typename: 'FormError'
                                                                                                                    }
                                                                                                                }
                                                                                                            },
                                                                                                            update: (proxy, {data: {adminUserUploadImgProfil: {file: {id, publicPath}}}}) => {
                                                                                                                let user = Object.assign({}, item);
                                                                                                                user.imgProfil = {
                                                                                                                    id,
                                                                                                                    publicPath,
                                                                                                                    __typename: 'File',
                                                                                                                };
                                                                                                                this.updateCache(proxy, {user});
                                                                                                            },
                                                                                                        });
                                                                                                    }
                                                                                                }}
                                                                                            />
                                                                                        </div>
                                                                                    );
                                                                                }}
                                                                            </Mutation>
                                                                        </TableCell>
                                                                        <TableCell>
                                                                            <Switch
                                                                                checked={isActive}
                                                                                onClick={() => {
                                                                                    let newUser = Object.assign({}, item);
                                                                                    newUser.isActive = !isActive;
                                                                                    adminUserEditProfil({
                                                                                        variables: {
                                                                                            id: item.id,
                                                                                            enable: newUser.isActive
                                                                                        },
                                                                                        refetchQueries: [{ query: adminUserGql, variables: {page, limit, orderBy, orderDir} }],
                                                                                        optimisticResponse: {
                                                                                            __typename: "Mutation",
                                                                                            adminUserEditProfil: {
                                                                                                __typename: 'User',
                                                                                                user: newUser,
                                                                                                errors: {
                                                                                                    key: null,
                                                                                                    message: null,
                                                                                                    __typename: 'FormError'
                                                                                                }
                                                                                            }
                                                                                        },
                                                                                        update: (proxy, {data: {adminUserEditProfil}}) => {
                                                                                            this.updateCache(proxy, adminUserEditProfil);
                                                                                        },
                                                                                    })
                                                                                }}
                                                                                value="checkedA"
                                                                            />
                                                                        </TableCell>
                                                                    </TableRow>
                                                                );
                                                            }}
                                                        </Mutation>
                                                    );
                                                })}

                                                {!loading && adminUsers.items.length === 0 &&
                                                    <TableRow style={{height: spinnerCellHeight}}>
                                                        <TableCell colSpan={5} style={{textAlign: 'center'}}>{t('no_data')}</TableCell>
                                                    </TableRow>
                                                }
                                            </TableBody>
                                            <TableFooter>
                                                {this.renderHeader()}
                                                {this.renderPagination(cpage, ccount, climit)}
                                            </TableFooter>
                                        </Table>
                                    </div>
                                </Paper>
                            );
                        }}
                    </Query>
                </Grid>
            </Grid>
        );
    }

    renderPagination(page, count, limit) {
        const {t, classes, theme} = this.props;

        let refSearchInput = null;

        return (
            <TableRow>
                <TableCell colSpan={6}>
                    <Toolbar className={classes.root}>
                        <div className={classes.pagination}>
                            <TextField
                                id="select-items-per-page"
                                select
                                label=""
                                className={classes.textField}
                                value={limit}
                                onChange={this.handleChangeLimit}
                                SelectProps={{
                                    MenuProps: {
                                        className: classes.menu,
                                    },
                                }}
                                margin="normal"
                            >
                                <MenuItem value={10}>10</MenuItem>
                                <MenuItem value={25}>25</MenuItem>
                                <MenuItem value={50}>50</MenuItem>
                                <MenuItem value={100}>100</MenuItem>
                            </TextField>
                            <IconButton
                                onClick={this.handleFirstPageButtonClick}
                                disabled={page === 1}
                                aria-label={t('page.first')}
                            >
                                {theme.direction === 'rtl' ? <LastPageIcon /> : <FirstPageIcon />}
                            </IconButton>
                            <IconButton
                                onClick={this.handleBackButtonClick}
                                disabled={page === 1}
                                aria-label={t('page.previous')}
                            >
                                {theme.direction === 'rtl' ? <KeyboardArrowRight /> : <KeyboardArrowLeft />}
                            </IconButton>
                            <IconButton
                                onClick={this.handleNextButtonClick}
                                disabled={page >= Math.ceil(count / limit)}
                                aria-label={t('page.next')}
                            >
                                {theme.direction === 'rtl' ? <KeyboardArrowLeft /> : <KeyboardArrowRight />}
                            </IconButton>
                            <IconButton
                                onClick={this.handleLastPageButtonClick.bind(this, count)}
                                disabled={page >= Math.ceil(count / limit)}
                                aria-label={t('page.last')}
                            >
                                {theme.direction === 'rtl' ? <FirstPageIcon /> : <LastPageIcon />}
                            </IconButton>
                        </div>
                        <div className={classes.spacer} />
                        <InputField
                            name={"search"}
                            id={"search"}
                            type={"text"}
                            label={t('search')}
                            fullWidth={false}
                            errors={null}
                            ref={node => {
                                if (node) {
                                    node = findDOMNode(node).getElementsByTagName('input')[0];
                                }

                                refSearchInput = node;
                            }}
                            endAdornment={
                                <SearchIcon
                                    style={{cursor: 'pointer'}}
                                    size={32}
                                    onClick={() => {
                                        this.setState({searches: JSON.stringify({0: refSearchInput.value})});
                                    }}
                                />
                            }
                        />
                    </Toolbar>
                </TableCell>
            </TableRow>
        );
    }

    renderHeader() {
        const {t} = this.props;

        const {orderBy, orderDir} = this.state;

        return(
            <TableRow>
                <TableCell
                    numeric={true}
                    sortDirection={orderBy === 'id' ? orderDir : false}
                >
                    <TableSortLabel
                        active={orderBy === 'id'}
                        direction={orderDir}
                        onClick={this.handleChangeOrder.bind(this, 'id', orderBy === 'id' ? orderDir === 'asc' ? 'desc' : 'asc' : orderDir)}
                    >
                        {t('id')}
                    </TableSortLabel>
                </TableCell>
                <TableCell
                    numeric={false}
                    sortDirection={orderBy === 'username' ? orderDir : false}
                >
                    <TableSortLabel
                        active={orderBy === 'username'}
                        direction={orderDir}
                        onClick={this.handleChangeOrder.bind(this, 'username', orderBy === 'username' ? orderDir === 'asc' ? 'desc' : 'asc' : orderDir)}
                    >
                        {t('username')}
                    </TableSortLabel>
                </TableCell>
                <TableCell
                    numeric={false}
                    sortDirection={orderBy === 'email' ? orderDir : false}
                >
                    <TableSortLabel
                        active={orderBy === 'email'}
                        direction={orderDir}
                        onClick={this.handleChangeOrder.bind(this, 'email', orderBy === 'email' ? orderDir === 'asc' ? 'desc' : 'asc' : orderDir)}
                    >
                        {t('email')}
                    </TableSortLabel>
                </TableCell>
                <TableCell
                    numeric={false}
                    sortDirection={orderBy === 'registrated_at' ? orderDir : false}
                >
                    <TableSortLabel
                        active={orderBy === 'registrated_at'}
                        direction={orderDir}
                        onClick={this.handleChangeOrder.bind(this, 'registrated_at', orderBy === 'registrated_at' ? orderDir === 'asc' ? 'desc' : 'asc' : orderDir)}
                    >
                        {t('registratedAt')}
                    </TableSortLabel>
                </TableCell>
                <TableCell>{t('imgProfil')}</TableCell>
                <TableCell>{t('is_active')}</TableCell>
            </TableRow>
        );
    }

    handleFirstPageButtonClick(event) {
        this.handleChangePage(event, 1);
    };

    handleBackButtonClick(event) {
        this.handleChangePage(event, this.state.page - 1);
    };

    handleNextButtonClick(event) {
        this.handleChangePage(event, this.state.page + 1);
    };

    handleLastPageButtonClick(total, event) {
        this.handleChangePage(
            event,
            Math.max(0, Math.ceil(total / this.state.limit)),
        );
    };

    handleChangePage(event, page) {
        this.setState({ page });
    };

    handleChangeLimit(event) {
        this.setState({ page: 1, limit: event.target.value });
    };

    handleChangeOrder(orderBy, orderDir) {
        this.setState({
            page: 1,
            orderBy,
            orderDir,
        })
    }

    editField(name, value) {
        let state = {};
        state[name] = value;

        this.setState(state);
    }

    updateCache(proxy, adminUserEditProfil) {
        const {page, limit, orderBy, orderDir} = this.state;
        const data = proxy.readQuery({
            query: adminUserGql,
            variables: {
                page,
                limit,
                orderBy,
                orderDir,
            }
        });

        let users = data.adminUsers.items;
        let cUser = adminUserEditProfil.user;
        for (let i = 0; i < users.length; i++) {
            let user = users[i];
            if (user.id === cUser.id) {
                data.adminUsers.items[i] = cUser;

                proxy.writeQuery({
                    query: adminUserGql,
                    variables: {
                        page,
                        limit,
                        orderBy,
                        orderDir,
                    },
                    data,
                });

                return;
            }
        }
    }
}

export default translate('admin_user')
(
    withApollo(
        withStyles(styles, {withTheme: true})
        (
            AdminUserListPage
        )
    )
);
