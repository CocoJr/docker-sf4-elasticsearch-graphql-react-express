/*
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

import gql from "graphql-tag";
import {FileFragment} from "./file";

export let UserBaseFragment = gql`
    fragment UserBaseFragment on User {
        id
        username
        email
        roles
        registratedAt
        isActive
        imgProfil {
            ...FileFragment
        }
    }
    ${FileFragment}
`;

export let UserAdminBaseFragment = gql`
    fragment UserAdminBaseFragment on User {
        ...UserBaseFragment
    }
    ${UserBaseFragment}
`;

export let UserFragment = gql`
    fragment UserFragment on User {
        ...UserBaseFragment
    }
    ${UserBaseFragment}
`;
